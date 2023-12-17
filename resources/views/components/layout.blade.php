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
    <header class="text-xl font-bold text-white bg-black"><div class="mx-auto container p-2 grid grid-cols-[1fr,auto]">
            <div>BotBuddy</div>
            @if(auth()->check() && auth()->user()->email_verified_at)<div class="md:hidden" id="toggle">☰</div>@endif
        </div>
    </header>
    @if(auth()->check() && auth()->user()->email_verified_at)
    <main class="min-h-full grid gap-2 grid-rows-[auto,1fr] md:grid-rows-[auto] md:grid-cols-[200px,1fr] md:mx-auto md:container">
        <x-nav />
        <div class="p-2 mx-auto container">{{ $slot }}</div>
    </main>
    @else
    <main class="min-h-full p-2">
        <div class="mx-auto max-w-md">{{ $slot }}</div>
    </main>
    @endif
    <footer class="sticky top-[100vh] p-2 mx-auto container">&copy; 2024 BotBuddy</footer>
</div>
@vite('resources/js/app.js')
</body>
</html>
