<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>@yield('title', 'Proje Durumu') · {{ config('app.name') }}</title>
        @include('partials.theme-init')
        @include('partials.fa')
        @include('partials.outfit')
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-outfit min-h-dvh bg-[#f4f0e8] text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        <header class="sticky top-0 z-20 border-b border-stone-200/80 bg-[#f4f0e8]/85 backdrop-blur-md dark:border-white/10 dark:bg-slate-950/80">
            <div class="mx-auto flex h-14 max-w-3xl items-center justify-between gap-3 px-4 sm:h-16 sm:px-6">
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-teal-700 sm:text-[11px] dark:text-teal-400">
                        Canlı durum
                    </p>
                    <p class="truncate text-sm font-semibold">{{ config('app.name') }}</p>
                </div>
                @include('partials.theme-toggle')
            </div>
        </header>

        <main class="pb-[max(1.5rem,env(safe-area-inset-bottom))]">
            @yield('content')
        </main>
    </body>
</html>
