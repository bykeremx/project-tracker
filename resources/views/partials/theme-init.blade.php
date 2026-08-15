<script>
    (() => {
        const stored = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = stored ?? (prefersDark ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', theme === 'dark');
        document.documentElement.style.colorScheme = theme;

        if (localStorage.getItem('sidebar-collapsed') === '1' && window.matchMedia('(min-width: 1024px)').matches) {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    })();
</script>
