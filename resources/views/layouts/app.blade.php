<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat mit Mia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" />
</head>

<body class="min-h-screen antialiased bg-gradient-to-br from-blue-400 via-fuchsia-100 to-pink-100">
    <main class="flex flex-col justify-center items-center min-h-screen">
        @yield('content')
    </main>
</body>

</html>
