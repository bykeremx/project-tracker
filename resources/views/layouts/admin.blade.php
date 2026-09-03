<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Admin') · {{ config('app.name') }}</title>
        @include('partials.theme-init')
        @include('partials.fa')
        @include('partials.outfit')
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        @php
            $navItems = [
                ['label' => 'Özet', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'icon' => 'fa-solid fa-chart-pie'],
                ['label' => 'Müşteriler', 'route' => 'admin.clients.index', 'match' => 'admin.clients.*', 'icon' => 'fa-solid fa-users'],
                ['label' => 'Projeler', 'route' => 'admin.projects.index', 'match' => 'admin.projects.*', 'icon' => 'fa-solid fa-diagram-project'],
                ['label' => 'Tahsilatlar', 'route' => 'admin.earnings.index', 'match' => 'admin.earnings.*', 'icon' => 'fa-solid fa-coins'],
                ['label' => 'Yöneticiler', 'route' => 'admin.admins.index', 'match' => 'admin.admins.*', 'icon' => 'fa-solid fa-user-shield'],
            ];
        @endphp

        <div id="admin-sidebar-overlay" class="fixed inset-0 z-30 bg-slate-950/50 backdrop-blur-[2px] lg:hidden"></div>

        <div class="flex min-h-screen overflow-x-clip">
            <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 flex shrink-0 flex-col border-r border-white/10 bg-slate-950 text-slate-300 lg:static">
                <div id="admin-sidebar-inner" class="flex h-full flex-col bg-[linear-gradient(180deg,rgb(15_23_42)_0%,rgb(2_6_23)_100%)]">
                    <div class="flex items-center justify-between border-b border-white/10 px-4 py-4">
                        <a href="{{ route('admin.dashboard') }}" class="group flex min-w-0 items-center gap-3 rounded-xl pr-1">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-teal-400/15 text-teal-300 ring-1 ring-teal-400/25 transition duration-200 group-hover:bg-teal-400/25">
                                <i class="fa-solid fa-layer-group"></i>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-[11px] font-semibold uppercase tracking-[0.2em] text-teal-400">Yönetim</span>
                                <span class="mt-0.5 block truncate text-base font-semibold text-white transition group-hover:text-teal-100">{{ config('app.name') }}</span>
                            </span>
                        </a>
                        <button type="button" data-sidebar-toggle class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-white/10 hover:text-white lg:hidden" aria-label="Menüyü kapat" aria-expanded="true" aria-controls="admin-sidebar">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <nav class="flex flex-1 flex-col gap-1.5 p-3" aria-label="Yönetim menüsü">
                        <p class="px-2 pb-1 pt-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Sayfalar</p>
                        @foreach ($navItems as $item)
                            @php $active = request()->routeIs($item['match']); @endphp
                            <a href="{{ route($item['route']) }}" class="sidebar-link {{ $active ? 'is-active' : '' }}" @if ($active) aria-current="page" @endif>
                                @if ($active)
                                    <span class="absolute top-[18%] bottom-[18%] left-0 w-[3px] rounded-full bg-teal-400"></span>
                                @endif
                                <span class="sidebar-link-icon">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                                <span class="sidebar-link-label">{{ $item['label'] }}</span>
                                <i class="fa-solid fa-chevron-right sidebar-link-arrow" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </nav>
                    <div class="border-t border-white/10 p-3">
                        <div class="mb-2 flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-2.5 py-2">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-teal-400/15 text-xs text-teal-300">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <p class="min-w-0 truncate text-xs font-medium text-slate-200">{{ auth()->user()->name }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-link sidebar-link-danger w-full">
                                <span class="sidebar-link-icon">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </span>
                                <span class="sidebar-link-label">Çıkış yap</span>
                                <i class="fa-solid fa-chevron-right sidebar-link-arrow" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/90 backdrop-blur dark:border-white/10 dark:bg-slate-950/80">
                    <div class="flex items-center gap-3 px-4 py-3 lg:px-8">
                        <button type="button" data-sidebar-toggle class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 transition duration-300 hover:border-teal-200 hover:bg-teal-50 dark:border-white/10 dark:hover:border-teal-400/30 dark:hover:bg-white/10" aria-label="Kenar çubuğunu aç veya kapat" aria-expanded="true" aria-controls="admin-sidebar">
                            <i class="fa-solid fa-bars" data-sidebar-icon></i>
                        </button>

                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-sm font-semibold lg:hidden">
                            <i class="fa-solid fa-layer-group text-teal-600 dark:text-teal-400"></i>
                            {{ config('app.name') }}
                        </a>

                        <nav class="hidden items-center gap-1 md:flex">
                            @foreach ($navItems as $item)
                                <a href="{{ route($item['route']) }}" class="nav-link inline-flex items-center gap-2 {{ request()->routeIs($item['match']) ? 'bg-teal-50 text-teal-800 dark:bg-teal-400/10 dark:text-teal-200' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/10' }}">
                                    <i class="{{ $item['icon'] }}"></i>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </nav>

                        <div class="ml-auto flex items-center gap-2">
                            @include('partials.theme-toggle')
                            <a href="{{ route('admin.clients.index') }}" class="btn-secondary">
                                <i class="fa-solid fa-users"></i>
                                <span class="hidden sm:inline">Müşteriler</span>
                            </a>
                            <a href="{{ route('admin.projects.index') }}" class="btn-secondary">
                                <i class="fa-solid fa-diagram-project"></i>
                                <span class="hidden sm:inline">Projeler</span>
                            </a>
                            <a href="{{ route('admin.projects.create') }}" class="btn-primary">
                                <i class="fa-solid fa-plus"></i>
                                <span class="hidden sm:inline">Yeni proje</span>
                            </a>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-4 py-8 lg:px-8">
                    @if (session('success'))
                        <div class="animate-fade-up mb-6 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                            <i class="fa-solid fa-circle-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="animate-pop mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-200">
                            <p class="mb-2 flex items-center gap-2 font-medium">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                Form hataları
                            </p>
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
