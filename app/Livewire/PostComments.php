<?php

namespace app\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;
use Mews\Purifier\Facades\Purifier;


class PostComments extends Component
{

    public Post $post;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $content = '';

    // Edit state
    public ?int $editingCommentId = null;
    public string $editContent = '';

    // Messages
    public string $successMessage = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|min:3|max:5000',
        ]; 
    }

    protected function messages(): array
    {
        return [
            'content.min' => 'Please provide a meaningful comment (at least 3 characters).',
        ];
    }

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    public function addComment(): void
    {
        $this->validate();

        // Sanitize inputs
        $sanitizedName = Purifier::clean($this->name, 'text');
        $sanitizedEmail = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $sanitizedContent = Purifier::clean($this->content, 'comment');
        
        // Check for URLs if not allowed
        if (! config('comments.spam_protection.allow_urls', true) &&  preg_match('/\b(?:https?:\/\/|www\.)/i', $sanitizedContent)) {
        $this->addError('content', 'URLs are not allowed in comments.');
        return;
        }

        // Check for duplicate
        $duplicateCheckHours = config('comments.spam_prevention.duplicate_check_hours', 24);
        $isDuplicate = Comment::where('email', $sanitizedEmail)
            ->where('post_id', $this->post->id)
            ->where('content', $sanitizedContent)
            ->where('created_at', '>', now()->subHours($duplicateCheckHours))
            ->exists();

        if ($isDuplicate) {
            $this->addError('content', 'This comment has already been submitted.');
            return;
        }

        // Rate Limiting
        $maxAttempts = config('comments.rate_limit.max_attempts', 3);
        $decayMinutes = config('comments.rate_limit.decay_minutes', 5);

        $recentComments = Comment::where('email', $sanitizedEmail)
            ->where('created_at', '>', now()->subMinutes($decayMinutes))
            ->count();

        if ($recentComments >= $maxAttempts) {
            $this->addError('email', 'You are posting too quickly. Please wait a few minutes.');
            return;
        }

        // Create comment
        Comment::create([
            'post_id' =>$this->post->id,
            'name' => $sanitizedName,
            'email' => $sanitizedEmail,
            'content' => $sanitizedContent,
            'approved' => config('comments.moderation.auto_approve', false),
            'ip_address' => config('comments.moderation.track_ip', true) ? request()->ip() : null,
        ]);

        // Reset form
        $this->reset(['name', 'email', 'content']);

        // Show success message
        $this->successMessage = config('comments.moderation.auto_approve', false)
        ? 'Comment posted successfully!'
        : 'Comment submitted! It will appear after approval.';

        // Refresh post to get updated comments
        $this->post->refresh();
    }

    public function startEditing(int $commentId): void
    {
        $comment = Comment::find($commentId);
        if ($comment) {
            $this->editingCommentId = $commentId;
            $this->editContent = $comment->content;
        }
    }

    public function cancelEditing(): void
    {
        $this->editingCommentId = null;
        $this->editContent = '';
    }

    public function updateComment(): void
    {
        $this->validate([
            'editContent' => 'required|string|min:3|max:5000',
        ]);

        $comment = Comment::find($this->editingCommentId);

        if ($comment) {
            $comment->update([
                'content' => Purifier::clean($this->editContent, 'comment'),
            ]);
            
            $this->cancelEditing();
            $this->successMessage = 'Comment updated!';
            $this->post->refresh();
        }
    }

    public function deleteComment(int $commentId): void
    {
        $comment = Comment::find($commentId);

        if ($comment) {
            $comment->delete();
            $this->successMessage = 'Comment deleted!';
            $this->post->refresh();
        }
    }

    public function render()
    {
        return view('livewire.post-comments', [
            'comments' => $this->post->approvedComments,
        ]);
    }

}