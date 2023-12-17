<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BotBuddy</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="min-h-screen grid grid-rows-[auto,1fr,auto] font-arial">
    <header class="mx-auto container text-lg font-bold p-2">BotBuddy</header>
    @auth
    <main class="min-h-full grid gap-2 grid-rows-[auto,1fr] md:grid-rows-[auto] md:grid-cols-[200px,1fr] mx-auto container p-2">
        <x-nav />
        <div>{{ $slot }}</div>
    </main>
    @else
    <main class="min-h-full p-2">
        <div class="mx-auto max-w-md">{{ $slot }}</div>
    </main>
    @endif
    <footer class="sticky top-[100vh] p-2 mx-auto container">&copy; 2024 BotBuddy</footer>
</div>
</body>
</html>
