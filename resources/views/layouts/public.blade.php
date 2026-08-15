<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Proje Durumu') · {{ config('app.name') }}</title>
        @include('partials.theme-init')
        @include('partials.fa')
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/80 backdrop-blur dark:border-white/10 dark:bg-slate-950/80">
            <div class="mx-auto flex h-14 max-w-3xl items-center justify-between px-4">
                <p class="flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-layer-group text-teal-600 dark:text-teal-400"></i>
                    {{ config('app.name') }}
                </p>
                @include('partials.theme-toggle')
            </div>
        </header>
        @yield('content')
    </body>
</html>
