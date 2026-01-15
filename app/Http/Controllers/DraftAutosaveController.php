<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\DraftAutosave;

class DraftAutosaveController extends Controller
{
    /**
     * Save draft automatically
     */
    public function save(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'draft_key' => 'required|string',
            'post_id' => 'nullable|integer|exists:posts,id',
            'title' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'status' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        $validated['user_id'] = Auth::id();

        // Update or create draft
        $draft = DraftAutosave::updateOrCreate(
            ['draft_key' => $validated['draft_key']],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Draft saved',
            'saved_at' => $draft->updated_at->format('g:i A'),
        ]);
    }

    /**
     * Load saved draft
     */
    public function load(Request $request): JsonResponse
    {
        $draftKey = $request->query('draft_key');


        $draft = DraftAutosave::where('draft_key', $draftKey)
            ->where('user_id', Auth::id())
            ->first();

        if (!$draft) {
            return response()->json([
                'success' => false,
                'message' => 'No drafts found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'draft' => $draft,
        ]);
    }

    /**
     * Delete draft
     */

    public function delete(Request $request): JsonResponse
    {
        $draftKey = $request->input('draft_key');

        DraftAutosave::where('draft_key', $draftKey)
            ->where('user_id', Auth::id())
            ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Draft deleted',
            ]);
    }
}
