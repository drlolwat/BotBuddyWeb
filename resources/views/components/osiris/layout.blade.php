<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BotBuddy</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="min-h-screen bg-gray-900">
    <div>
        <x-osiris.off-canvas-menu/>
        <x-osiris.static-sidebar/>
        <div class="xl:pl-72">
            <x-osiris.sticky-header page="{{ $page }}"/>
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

</div>
@vite('resources/js/app.jsx')
<script>
    const backdrop = document.getElementById('backdrop');
    const offCanvasMenu = document.getElementById('offCanvasMenu');
    const toggleButton = document.getElementById('toggleButton');

    const toggleSidebar = () => {
        backdrop.classList.add('transition-opacity', 'ease-linear', 'duration-300', 'opacity-100');
        offCanvasMenu.classList.add('transition', 'ease-in-out', 'duration-300', 'transform', 'translate-x-0');
        toggleButton.classList.add('ease-in-out', 'duration-300', 'opacity-100');

        backdrop.classList.toggle('opacity-100');
        backdrop.classList.toggle('opacity-0');
        offCanvasMenu.classList.toggle('translate-x-0');
        offCanvasMenu.classList.toggle('-translate-x-full');
        toggleButton.classList.toggle('opacity-100');
        toggleButton.classList.toggle('opacity-0');
    }

    toggleButton.addEventListener('click', toggleSidebar);

    document.body.addEventListener('click', (event) => {
        if (event.target.tagName === 'A') {
            toggleSidebar();
        }
    });
</script>
</body>
</html>
