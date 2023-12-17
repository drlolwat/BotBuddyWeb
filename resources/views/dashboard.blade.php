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
    <div>
        <div class="p-2">
            <div class="mx-auto container text-lg font-bold">BotBuddy</div>
        </div>
    </div>
    <div class="min-h-full">
        <div class="p-2">
            <div class="mx-auto container">
                <div>Welcome {{ auth()->user()->name }}</div>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="border rounded-lg py-1 px-2">Logout</button>
                </form>
            </div>
        </div>
    </div>
    <footer class="sticky top-[100vh]">
        <div class="mx-auto container p-2">&copy; 2024 BotBuddy</div>
    </footer>
</div>
</body>
</html>
