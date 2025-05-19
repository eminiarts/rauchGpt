<div class="w-full h-[90vh] max-w-lg flex flex-col bg-white rounded-lg shadow-lg overflow-hidden"
     x-data
     x-init="$nextTick(() => {
         const chatArea = document.getElementById('chat-area');
         if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
         document.getElementById('user-input')?.focus();
     });
     Livewire.hook('message.processed', () => {
         const chatArea = document.getElementById('chat-area');
         if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
         document.getElementById('user-input')?.focus();
     });">
    <div class="flex-shrink-0 p-4 bg-blue-50 border-b">
        <h1 class="text-xl font-bold text-center text-blue-700">rauchGPT – Demo Chatbot</h1>
        <p class="text-xs text-center text-gray-600">Langjähriger Raucher, der aufhören möchte, es aber nicht schafft.</p>
    </div>
    <div id="chat-area" class="overflow-y-auto flex-1 px-4 py-2 space-y-2 bg-gray-50">
        @forelse($messages as $msg)
            <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="{{ $msg['role'] === 'user' ? 'bg-gray-200 text-gray-900' : 'bg-blue-100 text-blue-900' }} rounded-lg px-3 py-2 max-w-[80%] text-sm shadow">
                    {{ $msg['text'] }}
                </div>
            </div>
        @empty
            <div class="flex justify-start">
                <div class="bg-blue-100 text-blue-900 rounded-lg px-3 py-2 max-w-[80%] text-sm shadow">Hallo, ich bin rauchGPT. Frag mich alles rund ums Rauchen aufhören…</div>
            </div>
        @endforelse
    </div>
    <form wire:submit.prevent="sendMessage" class="flex sticky bottom-0 gap-2 p-4 bg-white border-t">
        <input type="text" id="user-input" wire:model.defer="input" class="flex-1 px-4 py-2 text-sm rounded-full border focus:outline-none focus:ring focus:border-blue-400" placeholder="Deine Nachricht..." autocomplete="off">
        <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-full hover:bg-blue-700">Senden</button>
    </form>
    <div class="p-2 text-[11px] text-gray-500 text-center bg-gray-50 border-t">
        Der gesamte Chatverlauf wird <b>nur lokal</b> im Browser gespeichert (localStorage). Es werden <b>keine Daten an Dritte weitergegeben</b> oder auf Servern gespeichert. Die Antworten werden über die OpenAI API generiert, aber dort wird <b>kein Chatverlauf gespeichert</b>.
    </div>
</div>
