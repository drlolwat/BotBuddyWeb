<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BotBuddy - {{ $page }}</title>
    <meta name="description" content="BotBuddy is the ultimate OSRS bot manager tool, providing powerful automations, scheduling, and a supportive community for optimal bot farm management.">
    <meta name="keywords" content="OSRS, bot manager, OSRS bot, automation, scheduling, DreamBot, Jagex accounts, BotBuddy, Old School RuneScape, bot farm management">
    <meta name="author" content="BotBuddy">
    <meta property="og:title" content="BotBuddy - Ultimate OSRS Bot Manager Tool">
    <meta property="og:description" content="Optimize your OSRS bot farm with BotBuddy's advanced features, including automations, scheduling, and full support for Jagex accounts.">
    @if(env('GOOGLE_TAG_MANAGER_ID'))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_TAG_MANAGER_ID') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ env('GOOGLE_TAG_MANAGER_ID') }}');
    </script>
    @endif
    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900">
<main class="h-auto">
    {{ $slot }}
</main>
@vite('resources/js/app.jsx')
</body>
</html>
