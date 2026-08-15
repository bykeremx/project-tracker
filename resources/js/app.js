function getPreferredTheme() {
    const stored = window.localStorage.getItem('theme');

    if (stored === 'dark' || stored === 'light') {
        return stored;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    document.documentElement.style.colorScheme = theme;
    window.localStorage.setItem('theme', theme);
}

function initThemeToggle() {
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            applyTheme(nextTheme);
        });
    });
}

function isDesktopSidebar() {
    return window.matchMedia('(min-width: 1024px)').matches;
}

function setMobileSidebar(open) {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');

    sidebar?.classList.toggle('is-open', open);
    overlay?.classList.toggle('is-visible', open);
    document.body.classList.toggle('sidebar-open', open);
    syncSidebarToggles();
}

function syncSidebarToggles() {
    const sidebar = document.getElementById('admin-sidebar');
    const expanded = isDesktopSidebar()
        ? !document.documentElement.classList.contains('sidebar-collapsed')
        : Boolean(sidebar?.classList.contains('is-open'));

    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    });
}

function initSidebar() {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');

    if (!sidebar) {
        return;
    }

    syncSidebarToggles();

    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            if (isDesktopSidebar()) {
                const collapsed = !document.documentElement.classList.contains('sidebar-collapsed');
                document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
                window.localStorage.setItem('sidebar-collapsed', collapsed ? '1' : '0');
                syncSidebarToggles();
                return;
            }

            setMobileSidebar(!sidebar.classList.contains('is-open'));
        });
    });

    sidebar.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (!isDesktopSidebar()) {
                setMobileSidebar(false);
            }
        });
    });

    overlay?.addEventListener('click', () => setMobileSidebar(false));

    window.addEventListener('resize', () => {
        if (isDesktopSidebar()) {
            setMobileSidebar(false);
        }

        syncSidebarToggles();
    });
}

function initCopyButtons() {
    document.querySelectorAll('[data-copy-link]').forEach((button) => {
        button.addEventListener('click', async () => {
            const value = button.getAttribute('data-copy-link');
            const label = button.querySelector('[data-copy-text]') ?? button;

            if (!value) {
                return;
            }

            try {
                await navigator.clipboard.writeText(value);
                const original = label.textContent;
                label.textContent = 'Kopyalandı';
                button.classList.add('scale-105');
                window.setTimeout(() => {
                    label.textContent = original;
                    button.classList.remove('scale-105');
                }, 1600);
            } catch {
                window.prompt('Bağlantıyı kopyalayın:', value);
            }
        });
    });
}

function initInfiniteScroll() {
    const timeline = document.getElementById('timeline');
    const sentinel = document.getElementById('timeline-sentinel');
    const loading = document.getElementById('timeline-loading');

    if (!timeline || !sentinel) {
        return;
    }

    let nextCursor = timeline.dataset.nextCursor || '';
    let hasMore = timeline.dataset.hasMore === '1';
    let loadingMore = false;

    const observer = new IntersectionObserver(async (entries) => {
        if (!entries[0]?.isIntersecting || !hasMore || loadingMore || !nextCursor) {
            return;
        }

        loadingMore = true;
        loading?.classList.remove('hidden');

        const url = new URL(timeline.dataset.feedUrl, window.location.origin);
        url.searchParams.set('cursor', nextCursor);

        const response = await fetch(url.toString(), {
            headers: {
                Accept: 'application/json',
            },
        });

        if (response.ok) {
            const payload = await response.json();
            timeline.insertAdjacentHTML('beforeend', payload.html);
            nextCursor = payload.next_cursor || '';
            hasMore = Boolean(payload.has_more);
        }

        loadingMore = false;
        loading?.classList.add('hidden');
    });

    observer.observe(sentinel);
}

document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getPreferredTheme());
    initThemeToggle();
    initSidebar();
    initCopyButtons();
    initInfiniteScroll();
});
