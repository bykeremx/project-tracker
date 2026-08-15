<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Giriş · {{ config('app.name') }}</title>
        @include('partials.theme-init')
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f4f0e8] text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        <header class="sticky top-0 z-20 border-b border-stone-200/80 bg-[#f4f0e8]/80 backdrop-blur-md dark:border-white/10 dark:bg-slate-950/70">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-teal-700 dark:text-teal-400">Yönetim paneli</p>
                    <p class="text-sm font-semibold">{{ config('app.name') }}</p>
                </div>
                @include('partials.theme-toggle')
            </div>
        </header>

        <main class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-6xl items-center gap-10 px-4 py-10 sm:px-6 lg:grid-cols-2 lg:gap-16">
            <section class="relative overflow-hidden rounded-[2rem] bg-slate-950 px-8 py-12 text-white shadow-xl shadow-slate-900/10 dark:shadow-none lg:min-h-[34rem] lg:px-12">
                <div class="login-orb pointer-events-none absolute -top-16 -left-10 h-56 w-56 rounded-full bg-teal-400/30 blur-3xl"></div>
                <div class="login-orb pointer-events-none absolute right-0 bottom-0 h-64 w-64 rounded-full bg-amber-300/20 blur-3xl" style="animation-delay: -4s"></div>
                <p class="relative text-xs font-medium uppercase tracking-[0.28em] text-teal-300">Müşteri görünürlüğü</p>
                <h1 class="relative mt-4 max-w-sm text-4xl font-semibold tracking-tight sm:text-5xl">
                    Proje sürecini şeffaf takip edin.
                </h1>
                <p class="relative mt-5 max-w-md text-sm leading-6 text-slate-300">
                    Müşterilerinize canlı durum bağlantısı verin; içeride notlarınızı saklayın, dışarıda yalnızca paylaşmak istediklerinizi gösterin.
                </p>
                <ul class="relative mt-10 space-y-3 text-sm text-slate-200">
                    <li class="flex items-center gap-3">
                        <span class="h-1.5 w-1.5 rounded-full bg-teal-400"></span>
                        Benzersiz ve tahmin edilemez erişim anahtarı
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span>
                        Zaman çizelgesi ile adım adım ilerleme
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="h-1.5 w-1.5 rounded-full bg-sky-300"></span>
                        Yöneticiye özel iç notlar
                    </li>
                </ul>
            </section>

            <section class="animate-fade-up mx-auto w-full max-w-md">
                <div class="rounded-[1.75rem] border border-stone-200/80 bg-white p-8 shadow-lg shadow-stone-900/5 dark:border-white/10 dark:bg-slate-900/70 dark:shadow-black/30">
                    <h2 class="text-2xl font-semibold tracking-tight">Giriş yapın</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Yönetici hesabınızla devam edin.</p>

                    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">E-posta</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm outline-none transition focus:border-teal-400 focus:bg-white focus:ring-2 focus:ring-teal-500/20 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:bg-slate-950">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Şifre</label>
                            <input id="password" name="password" type="password" required
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm outline-none transition focus:border-teal-400 focus:bg-white focus:ring-2 focus:ring-teal-500/20 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:bg-slate-950">
                        </div>

                        <label class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                            <input type="checkbox" name="remember" value="1" class="rounded border-stone-300 text-teal-600 focus:ring-teal-500 dark:border-white/20 dark:bg-white/5">
                            Beni hatırla
                        </label>

                        <button type="submit" class="w-full rounded-xl bg-teal-600 px-4 py-3 text-sm font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:bg-teal-500 hover:shadow-lg hover:shadow-teal-600/20 active:translate-y-0 dark:bg-teal-400 dark:text-slate-950 dark:hover:bg-teal-300">
                            Giriş yap
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
