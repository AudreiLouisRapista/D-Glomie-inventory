(function () {
    function setDarkMode(enabled) {
        document.documentElement.classList.toggle('dark-mode', enabled);
        localStorage.setItem('theme', enabled ? 'dark' : 'light');

        var toggle = document.getElementById('themeToggle');
        if (toggle) {
            toggle.setAttribute('aria-pressed', enabled ? 'true' : 'false');
            toggle.querySelector('.theme-label').textContent = enabled ? 'Dark mode' : 'Light mode';
            toggle.querySelector('.theme-icon').className = enabled ? 'theme-icon fas fa-moon' : 'theme-icon fas fa-sun';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var savedTheme = localStorage.getItem('theme');
        setDarkMode(savedTheme === 'dark');

        var toggle = document.getElementById('themeToggle');
        if (toggle) {
            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                setDarkMode(!document.documentElement.classList.contains('dark-mode'));
            });
        }

        if (window.jQuery) {
            $(document).on('processing.dt', function (event, settings, processing) {
                var table = $(settings.nTable);
                table.closest('.main-card').toggleClass('skeleton-table-loading', processing);
            });
        }
    });

    window.addEventListener('load', function () {
        document.body.classList.remove('app-loading');
    });
})();
