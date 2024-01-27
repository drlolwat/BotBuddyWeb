<!DOCTYPE html>
<html lang="en">
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
<body class="antialiased bg-gray-50 dark:bg-gray-900">
<x-v1.nav />
<x-v1.aside />
<main class="p-4 md:ml-64 h-auto pt-20">{{ $slot }}</main>
<script>
    const newLayout = true;
</script>
@vite('resources/js/app.jsx')
</body>
</html>
