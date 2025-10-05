<?php

namespace App\Http\Controllers\Admin;

use App\Services\GeminiService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;


class AIController extends Controller
{

    protected $geminiService;
    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }
    public function show_chat()
    {
        return view('admin.ai.chat');
    }

    public function chat(Request $request)
    {
        $message = (string) $request->input('message', '');
        $history = (array) $request->input('history', []);

        if ($message === '') {
            return response()->json(['error' => 'Message is required'], 422);
        }

        try {
            $result = $this->geminiService->runAiQueryAndAnswer($message, $history);

            return response()->json([
                'ok' => true,
                'ai_text' => $result['text'],     // Model’s natural-language reply
                'sql' => $result['sql'],      // Final validated SQL
                'db_data' => $result['db_data'],  // Rows
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}