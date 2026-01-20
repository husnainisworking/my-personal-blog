<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class GroqService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
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
- Format content in HTML (use <h2>, <h3>, <p>, <ul>, <li>, <strong> tags)
- Do NOT include <h1> in content

IMPORTANT: Respond with ONLY a JSON object, no markdown, no code blocks:
{\"title\": \"Your title\", \"excerpt\": \"Brief SEO summary\", \"content\": \"<p>HTML content here</p>\"}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post($this->baseUrl, [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a JSON API. Always respond with valid JSON only, no markdown formatting.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 4096,
            'response_format' => ['type' => 'json_object'],
        ]);

        if (!$response->successful()) {
            throw new Exception('Groq API error: ' . $response->body());
        }

        $data = $response->json();
        $text = $data['choices'][0]['message']['content'] ?? null;

        if (!$text) {
            throw new Exception('No content generated');
        }

        // With response_format json_object, Groq returns clean JSON
        $result = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON: ' . json_last_error_msg());
        }

        return $result;
    }
}
