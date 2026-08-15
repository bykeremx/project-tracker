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

function initCopyButtons() {
    document.querySelectorAll('[data-copy-link]').forEach((button) => {
        button.addEventListener('click', async () => {
            const value = button.getAttribute('data-copy-link');

            if (!value) {
                return;
            }

            try {
                await navigator.clipboard.writeText(value);
                const original = button.textContent;
                button.textContent = 'Kopyalandı';
                button.classList.add('scale-105');
                window.setTimeout(() => {
                    button.textContent = original;
                    button.classList.remove('scale-105');
                }, 1600);
            } catch {
                window.prompt('Bağlantıyı kopyalayın:', value);
            }
        });
    });
}

function initAdminNav() {
    const toggle = document.getElementById('admin-nav-toggle');
    const drawer = document.getElementById('admin-mobile-nav');

    if (!toggle || !drawer) {
        return;
    }

    toggle.addEventListener('click', () => {
        const isOpen = !drawer.classList.contains('hidden');
        drawer.classList.toggle('hidden', isOpen);
        toggle.setAttribute('aria-expanded', String(!isOpen));
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
    initCopyButtons();
    initAdminNav();
    initInfiniteScroll();
});
