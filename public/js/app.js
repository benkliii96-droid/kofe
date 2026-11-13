document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.fade-in').forEach(function (el, i) {
        el.style.animationDelay = (i * 0.05) + 's';
    });
});
