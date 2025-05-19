<div class="w-full h-[100vh] max-w-lg flex flex-col bg-white rounded-lg shadow-lg overflow-hidden"
     x-data="chatbotStream()"
     x-init="init();
     Livewire.on('messagesUpdated', messages => save(messages));
     Livewire.hook('message.processed', () => {
         const chatArea = document.getElementById('chat-area');
         if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
         document.getElementById('user-input')?.focus();
     });">

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <div class="flex flex-shrink-0 justify-between items-center p-4 bg-blue-50 border-b">
        <div>
            <h1 class="text-xl font-bold text-blue-700">rauchGPT – Demo Chatbot</h1>
            <p class="text-xs text-gray-600">Langjähriger Raucher, der aufhören möchte, es aber nicht schafft.</p>
        </div>
        <button @click="resetChat" class="px-2 py-1 ml-2 text-xs text-red-600 rounded border border-red-200 hover:bg-red-50">Chat zurücksetzen</button>
    </div>
    <div id="chat-area" class="overflow-y-auto flex-1 px-4 py-2 space-y-2 bg-gray-50">
        <template x-for="(msg, idx) in messages" :key="idx">
            <div :class="'flex ' + (msg.role === 'user' ? 'justify-end' : 'justify-start')">
                <div :class="(msg.role === 'user' ? 'bg-gray-200 text-gray-900' : 'bg-blue-100 text-blue-900') + ' rounded-lg px-3 py-2 max-w-[80%] text-sm shadow'">
                    <span x-text="msg.text"></span>
                </div>
            </div>
        </template>
        <template x-if="messages.length === 0">
            <div class="flex justify-start">
                <div class="bg-blue-100 text-blue-900 rounded-lg px-3 py-2 max-w-[80%] text-sm shadow">Hallo, ich bin rauchGPT. Frag mich alles rund ums Rauchen aufhören…</div>
            </div>
        </template>
    </div>
    <form @submit.prevent="sendMessage" class="flex sticky bottom-0 gap-2 p-4 bg-white border-t">
        <input type="text" id="user-input" x-model="input" class="flex-1 px-4 py-2 text-sm rounded-full border focus:outline-none focus:ring focus:border-blue-400" placeholder="Deine Nachricht..." autocomplete="off">
        <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-full hover:bg-blue-700" :disabled="loading">Senden</button>
    </form>
    <div class="p-2 text-[11px] text-gray-500 text-center bg-gray-50 border-t">
        Der gesamte Chatverlauf wird <b>nur lokal</b> im Browser gespeichert (localStorage). Es werden <b>keine Daten an Dritte weitergegeben</b> oder auf Servern gespeichert. Die Antworten werden über die OpenAI API generiert, aber dort wird <b>kein Chatverlauf gespeichert</b>.
    </div>
</div>

<script>
    function chatbotStream() {
        return {
            messages: [],
            input: '',
            loading: false,
            init() {
                const saved = localStorage.getItem('rauchgpt_chat');
                if (saved) {
                    this.messages = JSON.parse(saved);
                }
            },
            save(messages) {
                localStorage.setItem('rauchgpt_chat', JSON.stringify(messages));
            },
            resetChat() {
                this.messages = [];
                this.save(this.messages);
            },
            async sendMessage() {
                if (!this.input.trim()) return;
                this.loading = true;
                this.messages.push({
                    role: 'user',
                    text: this.input
                });
                this.save(this.messages);
                // Add empty assistant message for streaming
                this.messages.push({
                    role: 'assistant',
                    text: ''
                });
                this.save(this.messages);
                // Only allow non-empty content, except for the last assistant message
                const history = this.messages
                    .filter((m, idx) => (typeof m.text === 'string' && m.text.length > 0) || idx === this.messages.length - 1)
                    .map(m => ({
                        role: m.role,
                        content: typeof m.text === 'string' ? m.text : ''
                    }));
                let botMsgIdx = this.messages.length - 1;
                let controller = new AbortController();
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                try {
                    const response = await fetch('/api/chat/stream', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'text/event-stream',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({
                            messages: history
                        }),
                        signal: controller.signal
                    });
                    if (!response.body) throw new Error('No response body');
                    const reader = response.body.getReader();
                    let botText = '';
                    let done = false;
                    let decoder = new TextDecoder();
                    while (!done) {
                        const {
                            value,
                            done: doneReading
                        } = await reader.read();
                        done = doneReading;
                        if (value) {
                            const chunk = decoder.decode(value, {
                                stream: true
                            });
                            chunk.split(/\n\n/).forEach(line => {
                                if (line.startsWith('data: ')) {
                                    const data = line.slice(6);
                                    if (data === '[DONE]') {
                                        done = true;
                                        return;
                                    }
                                    try {
                                        const parsed = JSON.parse(data);
                                        if (parsed.delta) {
                                            botText += parsed.delta;
                                            this.messages[botMsgIdx].text = botText;
                                            this.save(this.messages);
                                        }
                                    } catch {}
                                }
                            });
                        }
                    }
                } catch (e) {
                    this.messages[botMsgIdx].text = 'Fehler beim Streamen der Antwort.';
                }
                this.loading = false;
                this.save(this.messages);
                this.input = '';
                this.$nextTick(() => {
                    const chatArea = document.getElementById('chat-area');
                    if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
                    document.getElementById('user-input')?.focus();
                });
            }
        }
    }
</script>
