<?php

namespace App\Livewire;

use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class Chatbot extends Component
{
    public $messages = [];
    public $input = '';
    public $botStreaming = false;
    public $botReplyFull = '';

    public function sendMessage()
    {
        $this->validate([
            'input' => 'required|string|max:500',
        ]);
        $this->messages[] = [
            'role' => 'user',
            'text' => $this->input,
        ];

        $this->botStreaming = true;
        $this->botReplyFull = '';
        // Push an empty bot message for streaming
        $this->messages[] = [
            'role' => 'bot',
            'text' => '',
        ];
        $this->input = '';
        $this->dispatch('messagesUpdated', $this->messages);
        $this->dispatch('botStreaming', true);

        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4.1-2025-04-14',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Du bist ein langjähriger Raucher, der gerne aufhören möchte, es aber einfach nicht schafft. Antworte ehrlich, menschlich, manchmal humorvoll, aber immer aus der Sicht eines Menschen, der mit dem Aufhören kämpft. Antworte auf Deutsch.',
                    ],
                    ...array_map(function($msg) {
                        return [
                            'role' => $msg['role'],
                            'content' => $msg['text'],
                        ];
                    }, $this->messages),
                ],
            ]);
            $this->botReplyFull = $result->choices[0]->message->content ?? 'Fehler: Keine Antwort von OpenAI.';
        } catch (\Exception $e) {
            logger($e->getMessage());
            $this->botReplyFull = 'Fehler beim Abrufen der Antwort von OpenAI: ' . $e->getMessage();
        }
        // Start streaming the answer
        $this->streamBotReply();
    }

    public function streamBotReply()
    {
        $full = $this->botReplyFull;
        $current = '';
        $index = 0;
        while ($index < mb_strlen($full)) {
            $current .= mb_substr($full, $index, 1);
            $this->messages[count($this->messages) - 1]['text'] = $current;
            $this->dispatch('messagesUpdated', $this->messages);
            usleep(20000); // 20ms per character
            $index++;
        }
        $this->botStreaming = false;
        $this->dispatch('botStreaming', false);
    }

    public function resetChat()
    {
        $this->messages = [];
        $this->dispatch('messagesUpdated', $this->messages);
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
