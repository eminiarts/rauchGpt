<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat mit Frau Dr. Martin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center min-h-screen">
    <div class="w-full h-[95vh] max-w-lg flex flex-col bg-white/80 rounded-2xl shadow-2xl overflow-hidden backdrop-blur-md my-6"
         x-data="chatbotStream()"
         x-init="init()">

        <div class="flex flex-shrink-0 gap-3 items-center p-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 border-b">
            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-md">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white drop-shadow">Chat mit Frau Dr. Martin</h1>
                <p class="text-xs text-white/80">Ärztin für Herzkreislauferkrankungen</p>
            </div>
            <button @click="resetChat" class="px-3 py-1 ml-auto text-xs text-indigo-700 rounded-full border border-indigo-200 transition bg-white/80 hover:bg-indigo-50">Chat zurücksetzen</button>
        </div>
        
        <div id="chat-area" class="overflow-y-auto flex-1 px-4 py-2 space-y-3 bg-white/60">
            <template x-for="(msg, idx) in messages" :key="idx">
                <template x-if="msg.text && msg.text.trim() !== ''">
                    <div :class="'flex ' + (msg.role === 'user' ? 'justify-end' : 'justify-start') + ' items-end'">
                        <template x-if="msg.role === 'assistant'">
                            <div class="mr-2 w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                        </template>
                        <div :class="(msg.role === 'user' ? 'bg-gradient-to-br from-gray-200 to-gray-100 text-gray-900' : 'bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 text-indigo-900') + ' rounded-2xl px-4 py-2 max-w-[80%] text-base shadow-md font-medium'">
                            <span x-text="msg.text"></span>
                        </div>
                    </div>
                </template>
            </template>
            <template x-if="messages.length === 0">
                <div class="flex justify-start items-end">
                    <div class="mr-2 w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div class="bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 text-indigo-900 rounded-2xl px-4 py-2 max-w-[80%] text-base shadow-md font-medium">
                        Guten Tag! Ich bin Frau Dr. Martin, Ärztin für Herzkreislauferkrankungen. Wie kann ich Ihnen helfen?
                    </div>
                </div>
            </template>
            <template x-if="loading">
                <div class="flex justify-start items-end">
                    <div class="mr-2 w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div class="bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 text-indigo-900 rounded-2xl px-4 py-2 max-w-[80%] text-base shadow-md font-medium flex items-center gap-2">
                        <span>Dr. Martin tippt</span>
                        <span class="inline-flex">
                            <span class="animate-bounce" style="animation-delay:0ms">.</span>
                            <span class="animate-bounce" style="animation-delay:150ms">.</span>
                            <span class="animate-bounce" style="animation-delay:300ms">.</span>
                        </span>
                    </div>
                </div>
            </template>
        </div>
        
        <form @submit.prevent="sendMessage" class="flex sticky bottom-0 gap-2 p-4 border-t bg-white/80">
            <input type="text" id="user-input" x-model="input" class="flex-1 px-4 py-2 text-base rounded-full border transition focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" placeholder="Ihre Frage..." autocomplete="off">
            <button type="submit" class="px-5 py-2 text-base text-white bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full shadow transition hover:from-purple-500 hover:to-indigo-500" :disabled="loading">Senden</button>
        </form>
        
        <div class="p-2 text-[11px] text-gray-500 text-center bg-white/60 border-t">
            Der gesamte Chatverlauf wird <b>nur lokal</b> im Browser gespeichert (localStorage). Es werden <b>keine Daten an Dritte weitergegeben</b> oder auf Servern gespeichert. Die Antworten werden über die OpenAI API generiert, aber dort wird <b>kein Chatverlauf gespeichert</b>.
        </div>
    </div>

    <script>
        function chatbotStream() {
            return {
                messages: [],
                input: '',
                loading: false,
                scrollToBottom() {
                    this.$nextTick(() => {
                        const chatArea = document.getElementById('chat-area');
                        if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
                    });
                },
                init() {
                    const saved = localStorage.getItem('dr_martin_chat');
                    if (saved) {
                        this.messages = JSON.parse(saved);
                    }
                    this.scrollToBottom();
                },
                save(messages) {
                    localStorage.setItem('dr_martin_chat', JSON.stringify(messages));
                    this.scrollToBottom();
                },
                resetChat() {
                    this.messages = [];
                    this.save(this.messages);
                    this.scrollToBottom();
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
                        const response = await fetch('/api/dr-martin/stream', {
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
                                                this.scrollToBottom();
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
                    this.scrollToBottom();
                    document.getElementById('user-input')?.focus();
                }
            }
        }
    </script>
</body>
</html>