<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class OllamaService
{
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->baseUrl = config('services.ollama.url', 'http://127.0.0.1:11434');
        $this->model = config('services.ollama.model', 'tinyllama');
    }

    public function generatePost(string $topic, string $tone = 'professional', string $length = 'medium'): array
    {
        $wordCount = match ($length) {
            'short' => '300-500',
            'medium' => '600-900',
            'long' => '1000-1500',
            default => '600-900',
        };

        $prompt = "Write a blog post about: {$topic}

        Requirements:
        - Tone: {$tone}
        - Length: approximately {$wordCount} words
        - Include a catchy title
        - Write engaging introduction
        - Use subheadings to organize content
        - Include a conclusion
        - Format in HTML (use <h2>, <h3>, <p>, <ul>, <li>, <strong> tags)
        - Do NOT include the main <h1> title in the content body

        Return ONLY valid JSON in this exact format, no other text:
            {\"title\": \"Your title here\", \"excerpt\": \"A brief SEO-friendly summary\", \"content\": \"<p>Your HTML content here</p>\"}";

            $response = Http::timeout(180)->post("{$this->baseUrl}/api/generate", [
                'model' => $this->model,
                'prompt' => $prompt,
                'stream' => false,
            ]);

            if (!$response->successful()) {
                throw new Exception('Ollama API error: ' . $response->body());
            }

            $data = $response->json();
            $text = $data['response'] ?? null;

            if (!$text) {
                throw new Exception('No content generated');
            }

            // Extract JSON from response
            if (preg_match('/\{[^{}]*"title"[^{}]*"excerpt"[^{}]*"content"[^{}]*\}/s', $text, $matches)) {
                $text = $matches[0];
            }

            $text = preg_replace('/^```json\s*/i', '', $text);
            $text = preg_replace('/\s*```$s/', '', $text);
            $text = trim($text);

            $result = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'title' => 'Blog Post About ' . ucfirst($topic),
                    'excerpt' => substr(strip_tags($text), 0, 150) . '...',
                    'content' => '<p>' . nl2br(htmlspecialchars($text)) . '</p>',
                ];
            }

            return $result;
    }
}