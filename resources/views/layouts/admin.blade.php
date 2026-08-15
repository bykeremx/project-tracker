<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Admin') · {{ config('app.name') }}</title>
        @include('partials.theme-init')
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        @php
            $navItems = [
                ['label' => 'Özet', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard'],
                ['label' => 'Müşteriler', 'route' => 'admin.clients.index', 'match' => 'admin.clients.*'],
                ['label' => 'Projeler', 'route' => 'admin.projects.index', 'match' => 'admin.projects.*'],
            ];
        @endphp

        <div class="flex min-h-screen">
            <aside class="hidden w-64 shrink-0 flex-col bg-slate-950 text-slate-300 lg:flex">
                <div class="border-b border-white/10 px-6 py-5">
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-teal-400">Yönetim</p>
                    <h1 class="mt-1 text-lg font-semibold text-white">{{ config('app.name') }}</h1>
                </div>
                <nav class="flex flex-1 flex-col gap-1 p-4 text-sm">
                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['match']) ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
                <form method="POST" action="{{ route('logout') }}" class="border-t border-white/10 p-4">
                    @csrf
                    <p class="mb-3 truncate text-xs text-slate-400">{{ auth()->user()->name }}</p>
                    <button type="submit" class="w-full rounded-lg border border-white/10 px-3 py-2 text-left text-sm transition hover:bg-white/5">
                        Çıkış yap
                    </button>
                </form>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur dark:border-white/10 dark:bg-slate-950/80">
                    <div class="flex items-center gap-3 px-4 py-3 lg:px-8">
                        <button type="button" id="admin-nav-toggle" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 dark:border-white/10 lg:hidden" aria-expanded="false" aria-controls="admin-mobile-nav">
                            <span class="sr-only">Menü</span>
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/>
                            </svg>
                        </button>

                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold lg:hidden">{{ config('app.name') }}</a>

                        <nav class="hidden items-center gap-1 md:flex">
                            @foreach ($navItems as $item)
                                <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['match']) ? 'bg-teal-50 text-teal-800 dark:bg-teal-400/10 dark:text-teal-200' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/10' }}">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </nav>

                        <div class="ml-auto flex items-center gap-2">
                            @include('partials.theme-toggle')
                            <a href="{{ route('admin.clients.index') }}" class="btn-secondary">Müşteriler</a>
                            <a href="{{ route('admin.projects.index') }}" class="btn-secondary">Projeler</a>
                            <a href="{{ route('admin.projects.create') }}" class="btn-primary">Yeni proje</a>
                        </div>
                    </div>
                </header>

                <div id="admin-mobile-nav" class="hidden border-b border-slate-200 bg-white px-4 py-3 dark:border-white/10 dark:bg-slate-950 md:hidden">
                    <nav class="flex flex-col gap-1">
                        @foreach ($navItems as $item)
                            <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['match']) ? 'bg-teal-50 text-teal-800 dark:bg-teal-400/10 dark:text-teal-200' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/10' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link w-full text-left text-slate-500 dark:text-slate-400">Çıkış yap</button>
                        </form>
                    </nav>
                </div>

                <main class="flex-1 px-4 py-8 lg:px-8">
                    @if (session('success'))
                        <div class="animate-fade-up mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="animate-pop mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-200">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="animate-fade-up">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
