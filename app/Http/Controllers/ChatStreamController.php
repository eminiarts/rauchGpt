<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatStreamController extends Controller
{
    public function stream(Request $request)
    {
        $messages = $request->input('messages', []);
        $systemPrompt = [
            'role' => 'system',
            'content' => 'Du bist ein langjähriger Raucher, der gerne aufhören möchte, es aber einfach nicht schafft. Antworte ehrlich, menschlich, manchmal humorvoll, aber immer aus der Sicht eines Menschen, der mit dem Aufhören kämpft. Antworte auf Deutsch.',
        ];

        // Sanitize messages: ensure 'content' is a non-null string and role is valid
        $validRoles = ['system', 'user', 'assistant'];
        $messages = array_filter($messages, function ($msg, $idx) use ($messages, $validRoles) {
            // Allow empty content only for the last assistant message (for streaming)
            $isLast = $idx === array_key_last($messages);
            $roleValid = isset($msg['role']) && in_array($msg['role'], $validRoles);
            $contentValid = isset($msg['content']) && (is_string($msg['content']) && ($msg['content'] !== '' || $isLast));
            return $roleValid && $contentValid;
        }, ARRAY_FILTER_USE_BOTH);
        $messages = array_map(function ($msg) {
            return [
                'role' => $msg['role'],
                'content' => is_string($msg['content']) ? $msg['content'] : '',
            ];
        }, $messages);

        // dd($messages);

        return response()->stream(function () use ($messages, $systemPrompt) {
            $client = \OpenAI::client(config('openai.api_key'));
            $stream = $client->chat()->createStreamed([
                'model' => 'gpt-4.1-2025-04-14',
                'messages' => array_merge([$systemPrompt], $messages),
            ]);
            foreach ($stream as $response) {
                $delta = $response->choices[0]->delta->content ?? '';
                if ($delta !== '') {
                    echo "data: " . json_encode(['delta' => $delta]) . "\n\n";
                    ob_flush();
                    flush();
                }
            }
            echo "data: [DONE]\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
}
