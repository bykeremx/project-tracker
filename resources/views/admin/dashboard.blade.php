@extends('layouts.admin')

@section('title', 'Özet')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-semibold tracking-tight">Özet</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Müşteri ve proje durumuna genel bakış.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('admin.clients.index') }}" class="card-hover animate-fade-up p-5" style="animation-delay: 0ms">
            <p class="text-sm text-slate-500 dark:text-slate-400">Müşteriler</p>
            <p class="mt-2 text-3xl font-semibold">{{ $clientCount }}</p>
            <p class="mt-3 text-sm font-medium text-teal-700 dark:text-teal-300">Müşteri listesine git →</p>
        </a>
        <a href="{{ route('admin.projects.index') }}" class="card-hover animate-fade-up p-5" style="animation-delay: 60ms">
            <p class="text-sm text-slate-500 dark:text-slate-400">Toplam proje</p>
            <p class="mt-2 text-3xl font-semibold">{{ $projectCount }}</p>
            <p class="mt-3 text-sm font-medium text-teal-700 dark:text-teal-300">Proje listesine git →</p>
        </a>
        <a href="{{ route('admin.projects.index') }}" class="card-hover animate-fade-up p-5" style="animation-delay: 120ms">
            <p class="text-sm text-slate-500 dark:text-slate-400">Devam eden</p>
            <p class="mt-2 text-3xl font-semibold text-amber-600 dark:text-amber-400">{{ $inProgressCount }}</p>
            <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">Projeleri görüntüle</p>
        </a>
        <a href="{{ route('admin.projects.index') }}" class="card-hover animate-fade-up p-5" style="animation-delay: 180ms">
            <p class="text-sm text-slate-500 dark:text-slate-400">Tamamlanan</p>
            <p class="mt-2 text-3xl font-semibold text-emerald-600 dark:text-emerald-400">{{ $completedCount }}</p>
            <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">Projeleri görüntüle</p>
        </a>
    </div>

    <div class="card animate-fade-up mt-10" style="animation-delay: 220ms">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <h3 class="font-medium">Son projeler</h3>
            <a href="{{ route('admin.projects.index') }}" class="text-sm font-medium text-teal-700 transition hover:text-teal-600 dark:text-teal-300">Tümünü gör</a>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-white/10">
            @forelse ($recentProjects as $project)
                <a href="{{ route('admin.projects.show', $project) }}" class="flex items-center justify-between px-5 py-4 transition hover:bg-teal-50/60 dark:hover:bg-white/5">
                    <div>
                        <p class="font-medium">{{ $project->title }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $project->client->name }}</p>
                    </div>
                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $project->status->badgeClasses() }}">
                        {{ $project->status->label() }}
                    </span>
                </a>
            @empty
                <p class="px-5 py-8 text-sm text-slate-500 dark:text-slate-400">Henüz proje yok.</p>
            @endforelse
        </div>
    </div>
@endsection
