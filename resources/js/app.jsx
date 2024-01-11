const toggle = document.getElementById('toggle');
const nav = document.querySelector('nav');

if (toggle && nav) {
    toggle.addEventListener('click', function() {
        nav.classList.toggle('hidden');
    });
}
