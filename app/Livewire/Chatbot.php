<?php

namespace App\Livewire;

use Livewire\Component;

class Chatbot extends Component
{
    public $messages = [];
    public $input = '';

    public function sendMessage()
    {
        $this->validate([
            'input' => 'required|string|max:500',
        ]);
        $this->messages[] = [
            'role' => 'user',
            'text' => $this->input,
        ];
        // Placeholder bot response
        $this->messages[] = [
            'role' => 'bot',
            'text' => 'Das ist eine Beispielantwort. Bald antworte ich wie ein echter Raucher!',
        ];
        $this->input = '';
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
