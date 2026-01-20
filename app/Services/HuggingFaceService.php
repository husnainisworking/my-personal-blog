<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class HuggingFaceService
{
    protected $apiKey;
    protected $baseUrl = 'https://router.huggingface.co/hf-inference/models/Qwen/Qwen2.5-Coder-32B-Instruct/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.huggingface.api_key');
    }

    public function generatePost(string $topic, string $tone='professional', string $length='medium'): array
    {
        $wordCount = match ($length) {
            'short' => '300-500',
            'medium' => '600-900',
            'long' => '1000-1500',
            default => '600-900',
        };

        $prompt = "[INST] Write a blog post about: {$topic}
        
        Requirements:
        - Tone: {$tone}
        - Length: approximately {$wordCount} words
        - Include a catchy title
        - Write engaging introduction
        - Use subheadings (H2,H3) to organize content
        - Include a conclusion
        - Format in HTML (use <h2>, <h3>, <p>, <ul>, <li>, <strong> tags)
        - Do NOT include the main <h1> title in the content body

        Return ONLY valid JSON in this exact format, no other text:
            {\"title\": \"Your title\", \"excerpt\": \"SEO summary\", \"content\": \"<p>HTML content</p>\"}
            [/INST]";


            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post($this->baseUrl, [
                'model' => 'Qwen/Qwen2.5-Coder-32B-Instruct',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                    'max_tokens' => 2048,
                    'temperature' => 0.7,
                            ]);

            if (!$response->successful()) {
                throw new Exception('HuggingFace API error: '. $response->body());
            }
            
            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? null;

            if (!$text) {
                throw new Exception('No content generated');
            }

            // Extract JSON from response
            if (preg_match('/\{[^{}]*"title"[^{}]*"excerpt"[^{}]*"content"[^{}]*\}/s', $text, $matches)) {
                $text = $matches[0];
            }

            $text = preg_replace('/^```json\s*/i', '', $text);
            $text = preg_replace('/\s*```$/', '', $text);
            $text = trim($text);

            $result = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response from AI');
            }

            return $result;
            
        
    }
}