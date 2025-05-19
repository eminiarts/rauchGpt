<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>rauchGPT Demo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" />
</head>

<body class="bg-gray-100 min-h-screen antialiased">
    <header class="w-full bg-white shadow mb-6">
        <nav class="max-w-4xl mx-auto flex items-center justify-between p-4">
            <a href="/" class="text-lg font-bold text-gray-800">rauchGPT</a>
            <a href="/chatbot" class="text-blue-600 hover:underline">Chatbot Demo</a>
        </nav>
    </header>
    <main class="flex flex-col items-center justify-center">
        @yield('content')
    </main>
</body>

</html>
