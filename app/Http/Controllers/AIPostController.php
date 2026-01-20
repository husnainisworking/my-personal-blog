<?php

namespace App\Http\Controllers;

use App\Services\GroqService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class AIPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generate(Request $request, GroqService $ai): JsonResponse
    {

        $validator = \Validator::make($request->all(), [
            'topic' => 'required|string|min:3|max:500',
            'tone' => 'required|in:professional,casual,friendly,formal,humorous',
            'length' => 'required|in:short,medium,long',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $result = $ai->generatePost(
                $request->topic,
                $request->tone,
                $request->length
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate content: ' . $e->getMessage(),
            ], 500);
            }
        }
    }

