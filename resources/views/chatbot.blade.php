@extends('layouts.app')

@section('content')
    <div class="w-full h-[90vh] max-w-lg flex flex-col bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="flex-shrink-0 p-4 border-b bg-blue-50">
            <h1 class="text-xl font-bold text-blue-700 text-center">rauchGPT – Demo Chatbot</h1>
            <p class="text-xs text-gray-600 text-center">Langjähriger Raucher, der aufhören möchte, es aber nicht schafft.</p>
        </div>
        <div id="chat-area" class="flex-1 overflow-y-auto px-4 py-2 space-y-2 bg-gray-50">
            <!-- Beispielnachrichten, später dynamisch ersetzen -->
            <div class="flex justify-start">
                <div class="bg-blue-100 text-blue-900 rounded-lg px-3 py-2 max-w-[80%] text-sm shadow">Hallo, ich bin rauchGPT. Frag mich alles rund ums Rauchen aufhören…</div>
            </div>
            <div class="flex justify-end">
                <div class="bg-gray-200 text-gray-900 rounded-lg px-3 py-2 max-w-[80%] text-sm shadow">Warum willst du aufhören?</div>
            </div>
            <div class="flex justify-start">
                <div class="bg-blue-100 text-blue-900 rounded-lg px-3 py-2 max-w-[80%] text-sm shadow">Weil ich weiß, dass es gesünder wäre… aber es ist echt schwer.</div>
            </div>
        </div>
        <form id="chat-form" class="flex gap-2 p-4 border-t bg-white sticky bottom-0">
            <input type="text" id="user-input" class="flex-1 border rounded-full px-4 py-2 focus:outline-none focus:ring focus:border-blue-400 text-sm" placeholder="Deine Nachricht..." autocomplete="off">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 text-sm">Senden</button>
        </form>
        <div class="p-2 text-[11px] text-gray-500 text-center bg-gray-50 border-t">
            Der gesamte Chatverlauf wird <b>nur lokal</b> im Browser gespeichert (localStorage). Es werden <b>keine Daten an Dritte weitergegeben</b> oder auf Servern gespeichert. Die Antworten werden über die OpenAI API generiert, aber dort wird <b>kein Chatverlauf gespeichert</b>.
        </div>
    </div>
@endsection
