@php
    use App\Support\Money;
@endphp

@extends('layouts.admin')

@section('title', 'Özet')

@section('content')
    <section class="relative overflow-hidden rounded-3xl bg-slate-950 px-6 py-8 text-white shadow-xl shadow-slate-900/10 dark:shadow-none sm:px-8">
        <div class="login-orb pointer-events-none absolute -top-16 -left-10 h-56 w-56 rounded-full bg-teal-400/25 blur-3xl"></div>
        <div class="login-orb pointer-events-none absolute right-0 bottom-0 h-48 w-48 rounded-full bg-amber-300/15 blur-3xl" style="animation-delay: -4s"></div>
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.28em] text-teal-300">{{ now()->translatedFormat('l, d F Y') }}</p>
                <h2 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">{{ $greeting }}, {{ auth()->user()->name }}</h2>
                <p class="mt-2 max-w-xl text-sm text-slate-300">Müşteriler, projeler ve tahsilat tek bakışta. Bugün panele {{ $adminCount }} yönetici hesabı tanımlı.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-400 px-4 py-2.5 text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:bg-teal-300">
                    <i class="fa-solid fa-plus"></i>
                    Yeni proje
                </a>
                <a href="{{ route('admin.admins.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-white/10">
                    <i class="fa-solid fa-user-plus"></i>
                    Yönetici ekle
                </a>
            </div>
        </div>
    </section>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('admin.clients.index') }}" class="card-hover group animate-fade-up p-5" style="animation-delay: 0ms">
            <div class="flex items-start justify-between">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300">
                    <i class="fa-solid fa-users"></i>
                </span>
                <i class="fa-solid fa-arrow-up-right text-xs text-slate-300 transition group-hover:text-teal-500 dark:text-slate-600"></i>
            </div>
            <p class="mt-5 text-sm text-slate-500 dark:text-slate-400">Müşteriler</p>
            <p class="mt-1 text-3xl font-semibold tracking-tight">{{ $clientCount }}</p>
        </a>
        <a href="{{ route('admin.projects.index') }}" class="card-hover group animate-fade-up p-5" style="animation-delay: 50ms">
            <div class="flex items-start justify-between">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-teal-50 text-teal-700 dark:bg-teal-500/10 dark:text-teal-300">
                    <i class="fa-solid fa-diagram-project"></i>
                </span>
                <i class="fa-solid fa-arrow-up-right text-xs text-slate-300 transition group-hover:text-teal-500 dark:text-slate-600"></i>
            </div>
            <p class="mt-5 text-sm text-slate-500 dark:text-slate-400">Toplam proje</p>
            <p class="mt-1 text-3xl font-semibold tracking-tight">{{ $projectCount }}</p>
        </a>
        <a href="{{ route('admin.projects.index') }}" class="card-hover group animate-fade-up p-5" style="animation-delay: 100ms">
            <div class="flex items-start justify-between">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300">
                    <i class="fa-solid fa-spinner"></i>
                </span>
                <i class="fa-solid fa-arrow-up-right text-xs text-slate-300 transition group-hover:text-teal-500 dark:text-slate-600"></i>
            </div>
            <p class="mt-5 text-sm text-slate-500 dark:text-slate-400">Devam eden</p>
            <p class="mt-1 text-3xl font-semibold tracking-tight text-amber-600 dark:text-amber-400">{{ $inProgressCount }}</p>
        </a>
        <a href="{{ route('admin.admins.index') }}" class="card-hover group animate-fade-up p-5" style="animation-delay: 150ms">
            <div class="flex items-start justify-between">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300">
                    <i class="fa-solid fa-user-shield"></i>
                </span>
                <i class="fa-solid fa-arrow-up-right text-xs text-slate-300 transition group-hover:text-teal-500 dark:text-slate-600"></i>
            </div>
            <p class="mt-5 text-sm text-slate-500 dark:text-slate-400">Yöneticiler</p>
            <p class="mt-1 text-3xl font-semibold tracking-tight">{{ $adminCount }}</p>
        </a>
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-3">
        <a href="{{ route('admin.earnings.show', [now()->format('Y'), now()->format('m')]) }}" class="card-hover animate-fade-up p-5" style="animation-delay: 180ms">
            <p class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <i class="fa-solid fa-calendar-day text-emerald-500"></i>
                Bu ay tahsil
            </p>
            <p class="mt-3 text-3xl font-semibold tracking-tight text-emerald-700 dark:text-emerald-300">{{ Money::format($monthEarned) }}</p>
            <p class="mt-3 text-sm font-medium text-teal-700 dark:text-teal-300">Ay detayına git →</p>
        </a>
        <a href="{{ route('admin.earnings.index', ['year' => now()->year]) }}" class="card-hover animate-fade-up p-5" style="animation-delay: 210ms">
            <p class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <i class="fa-solid fa-calendar text-teal-500"></i>
                Bu yıl tahsil
            </p>
            <p class="mt-3 text-3xl font-semibold tracking-tight">{{ Money::format($yearEarned) }}</p>
            <p class="mt-3 text-sm font-medium text-teal-700 dark:text-teal-300">Ayları gör →</p>
        </a>
        <div class="card animate-fade-up p-5" style="animation-delay: 240ms">
            <p class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <i class="fa-solid fa-scale-balanced text-amber-500"></i>
                Kalan alacak
            </p>
            <p class="mt-3 text-3xl font-semibold tracking-tight text-amber-600 dark:text-amber-400">{{ Money::format($outstandingTotal) }}</p>
            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Anlaşılan bütçeden tahsil düşülür</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-5">
        <section class="card animate-fade-up p-5 xl:col-span-3" style="animation-delay: 270ms">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="flex items-center gap-2 font-medium">
                    <i class="fa-solid fa-chart-column text-teal-600 dark:text-teal-400"></i>
                    Aylık tahsilat
                </h3>
                <a href="{{ route('admin.earnings.index') }}" class="text-sm font-medium text-teal-700 transition hover:text-teal-600 dark:text-teal-300">Tüm aylar</a>
            </div>
            <div class="flex h-44 items-end gap-2 sm:gap-3">
                @foreach ($chartMonths as $row)
                    @php $height = max(8, (int) round(((float) $row['total'] / $chartMax) * 100)); @endphp
                    <a href="{{ route('admin.earnings.show', [$row['year'], $row['month']]) }}" class="group flex h-full min-w-0 flex-1 flex-col items-center justify-end gap-2">
                        <span class="sr-only">{{ $row['label'] }} · {{ Money::format($row['total']) }}</span>
                        <span class="w-full rounded-t-lg bg-teal-500/80 transition group-hover:bg-teal-400 dark:bg-teal-400/80 dark:group-hover:bg-teal-300" style="height: {{ $height }}%"></span>
                        <span class="truncate text-[11px] text-slate-500 dark:text-slate-400">{{ \Illuminate\Support\Str::substr($row['label'], 0, 3) }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="card animate-fade-up overflow-hidden xl:col-span-2" style="animation-delay: 300ms">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-white/10">
                <h3 class="flex items-center gap-2 font-medium">
                    <i class="fa-solid fa-clock-rotate-left text-teal-600 dark:text-teal-400"></i>
                    Son projeler
                </h3>
                <a href="{{ route('admin.projects.index') }}" class="text-sm font-medium text-teal-700 transition hover:text-teal-600 dark:text-teal-300">Tümü</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-white/10">
                @forelse ($recentProjects as $project)
                    <a href="{{ route('admin.projects.show', $project) }}" class="flex items-center justify-between gap-3 px-5 py-4 transition hover:bg-teal-50/60 dark:hover:bg-white/5">
                        <div class="min-w-0">
                            <p class="truncate font-medium">{{ $project->title }}</p>
                            <p class="mt-0.5 truncate text-sm text-slate-500 dark:text-slate-400">{{ $project->client->name }}</p>
                        </div>
                        @include('partials.status-badge', ['status' => $project->status])
                    </a>
                @empty
                    <p class="px-5 py-8 text-sm text-slate-500 dark:text-slate-400">Henüz proje yok.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
