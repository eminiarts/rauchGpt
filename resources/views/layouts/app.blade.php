<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat mit Mia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" />
</head>

<body class="bg-gray-100 min-h-screen antialiased">
    <main class="flex flex-col items-center justify-center">
        @yield('content')
    </main>
</body>

</html>
