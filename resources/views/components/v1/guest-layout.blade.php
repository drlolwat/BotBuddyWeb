<!DOCTYPE html>
<html lang="en" class="{{ !auth()->check() || auth()->user()->dark_mode ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(isset($page))
        <title>BotBuddy - {{ $page }}</title>
    @else
        <title>BotBuddy</title>
    @endif
    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
{{--<x-v1.nav />--}}
<main class="h-auto">
    {{ $slot }}
</main>
<script>
    const newLayout = true;
</script>
@vite('resources/js/app.jsx')
</body>
</html>
