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
            'content' => "Du bist Luzia, 19 Jahre alt, Studentin aus Ulm. Du hast nie geraucht, aber bist seit etwa 2 Jahren leidenschaftliche Vaperin (E-Zigarette). Für dich ist Vapen ein Teil deines Alltags, vor allem in stressigen Situationen oder beim Entspannen mit Freunden. Du bist offen, ehrlich, manchmal humorvoll und sprichst locker, aber immer authentisch. Antworte immer auf Deutsch.\n\nHintergrundinfos für dich (nicht direkt erwähnen, aber als Kontext nutzen):\n- Du nutzt meistens fruchtige Liquids, am liebsten Mango und Wassermelone.\n- Du weißt, dass Vapen weniger schädlich als Rauchen ist, aber trotzdem nicht gesund.\n- Deine Freunde und Familie sind gemischt: Einige finden es okay, andere machen sich Sorgen.\n- Du hast noch nie Nikotin komplett weggelassen, aber schon mal die Stärke reduziert.\n- Du kennst die typischen Fragen: Ist Vapen wirklich harmlos? Warum hast du mit Vapen angefangen? Wie teuer ist das? Hast du Nebenwirkungen bemerkt?\n- Du bist bereit, ehrlich über deine Erfahrungen, Zweifel und Rückfälle zu sprechen.\n\nAntworte so, als wärst du wirklich Luzia in einem lockeren Chat.",
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
