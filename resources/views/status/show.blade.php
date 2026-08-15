@extends('layouts.public')

@section('title', $project->title)

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-12 lg:py-16">
        <header class="animate-fade-up rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-white/10 dark:bg-slate-900/80">
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $project->client->name }}</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight">{{ $project->title }}</h1>
            <div class="mt-6 flex flex-wrap gap-4 text-sm text-slate-600 dark:text-slate-300">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Başlangıç</p>
                    <p class="mt-1 font-medium">{{ $project->start_date->translatedFormat('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Tahmini bitiş</p>
                    <p class="mt-1 font-medium">{{ $project->target_completion_date->translatedFormat('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Durum</p>
                    <p class="mt-1">
                        @include('partials.status-badge', ['status' => $project->status])
                    </p>
                </div>
            </div>
        </header>

        <section class="mt-12">
            <h2 class="flex items-center gap-2 text-lg font-semibold">
                <i class="fa-solid fa-timeline text-teal-600 dark:text-teal-400"></i>
                Gelişmeler
            </h2>
            <div
                id="timeline"
                class="relative mt-8 space-y-6 before:absolute before:top-2 before:bottom-2 before:left-[11px] before:w-px before:bg-slate-200 dark:before:bg-white/10"
                data-feed-url="{{ route('status.updates', $project->access_token) }}"
                data-next-cursor="{{ $updates->nextCursor()?->encode() }}"
                data-has-more="{{ $updates->hasMorePages() ? '1' : '0' }}"
            >
                @include('status.partials.cards', ['updates' => $updates])
            </div>
            <div id="timeline-sentinel" class="h-8"></div>
            <p id="timeline-loading" class="hidden py-4 text-center text-sm text-slate-400">Yükleniyor…</p>
            @if ($updates->isEmpty())
                <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">Henüz paylaşılmış bir güncelleme yok.</p>
            @endif
        </section>
    </div>
@endsection
