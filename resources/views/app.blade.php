<!DOCTYPE html>
<html class="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @viteReactRefresh
    @vite('resources/css/app.css')
    @vite('resources/js/app.jsx')
    <script>const useInertia = true;</script>
    @inertiaHead
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
@inertia
</body>
</html>
