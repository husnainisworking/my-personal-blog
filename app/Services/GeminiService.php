<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function generatePost (string $topic, string $tone = 'professional', string $length = 'medium'): array
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
        - Use subheadings (H2, H3) to organize content
        - Include a conclusion
        - Format in HTML (use <h2>, <h3>, <p>, <ul>, <li>, <strong> tags )
        - Do NOT include the main <h1> title in the content body

        Return your response in this exact JSON format:
        {
            \"title\": \"Your catchy title here\",
            \"excerpt\": \"A 1-2 sentence summary for SEO\",
            \"content\": \"<p>Your HTML content here...</p>\"
        }
            Only return the JSON, no other text.";
        
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                                'contents' => [
                                    [
                                        'parts' => [
                                            ['text' => $prompt]
                                        ]
                                    ]
                                        ],
                                    'generationConfig' => [
                                        'temperature' => 0.7,
                                        'maxOutputTokens' => 4096,
                                    ]
                                    ]);

                            if (!$response->successful()) {
                                throw new Exception('Gemini API error: ' . $response->body());
                            }

                            $data = $response->json();
                            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                            if (!$text) {
                                throw new Exception('No content generated');
                            }

                            // Clean markdown code blocks if present
                            $text = preg_replace('/^```json\s*/', '', $text);
                            $text = preg_replace('/\s*```$/', '', $text);
                            $text = trim($text);

                            $result = json_decode($text, true);

                            if (json_last_error() !== JSON_ERROR_NONE) {
                                throw new Exception('Invalid JSON response from AI');
                            }

                            return $result;
    }
}