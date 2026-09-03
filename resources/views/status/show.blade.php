@extends('layouts.public')

@section('title', $project->title)

@section('content')
    @php
        $today = now()->startOfDay();
        $start = $project->start_date->copy()->startOfDay();
        $target = $project->target_completion_date->copy()->startOfDay();
        $totalDays = max((int) $start->diffInDays($target), 1);
        $elapsedDays = (int) $start->diffInDays($today, false);
        $progress = $project->status === \App\Enums\ProjectStatus::Completed
            ? 100
            : (int) min(100, max(0, round(($elapsedDays / $totalDays) * 100)));
        $remainingDays = (int) $today->diffInDays($target, false);
        $latestUpdate = $updates->first();
    @endphp

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 sm:py-12 lg:py-16">
        <header class="animate-fade-up relative overflow-hidden rounded-[1.75rem] border border-stone-200/80 bg-white p-5 shadow-sm sm:rounded-[2rem] sm:p-8 dark:border-white/10 dark:bg-slate-900/80">
            <div class="pointer-events-none absolute -top-16 -right-10 h-40 w-40 rounded-full bg-teal-400/15 blur-3xl dark:bg-teal-400/10"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-10 h-40 w-40 rounded-full bg-amber-300/15 blur-3xl dark:bg-amber-300/10"></div>

            <div class="relative flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $project->client->name }}</p>
                    @if ($project->client->company_name)
                        <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">{{ $project->client->company_name }}</p>
                    @endif
                </div>
                @include('partials.status-badge', ['status' => $project->status])
            </div>

            <h1 class="relative mt-3 text-2xl font-semibold tracking-tight sm:mt-4 sm:text-3xl">{{ $project->title }}</h1>

            <p class="relative mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                @if ($project->status === \App\Enums\ProjectStatus::Completed)
                    Proje teslim edildi
                    @if ($project->actual_completion_date)
                        · {{ $project->actual_completion_date->translatedFormat('d M Y') }}
                    @endif
                @elseif ($remainingDays > 1)
                    Tahmini teslime {{ $remainingDays }} gün var
                @elseif ($remainingDays === 1)
                    Tahmini teslim yarın
                @elseif ($remainingDays === 0)
                    Tahmini teslim bugün
                @else
                    Tahmini teslim {{ abs($remainingDays) }} gün gecikti
                @endif
                @if ($latestUpdate)
                    <span class="mt-1 block sm:mt-0 sm:inline">
                        <span class="hidden sm:inline"> · </span>
                        Son gelişme {{ $latestUpdate->created_at->translatedFormat('d M Y') }}
                    </span>
                @endif
            </p>

            <div class="relative mt-5" aria-label="Takvim ilerlemesi">
                <div class="mb-2 flex items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                    <span>Takvim ilerlemesi</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">%{{ $progress }}</span>
                </div>
                <div class="h-2 overflow-hidden rounded-full bg-stone-100 dark:bg-white/10">
                    <div class="h-full rounded-full bg-teal-500 transition-[width] duration-500 dark:bg-teal-400" style="width: {{ $progress }}%"></div>
                </div>
            </div>

            <dl class="relative mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-stone-200/80 bg-stone-50/80 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                    <dt class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                        <i class="fa-regular fa-calendar"></i>
                        Başlangıç
                    </dt>
                    <dd class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $project->start_date->translatedFormat('d M Y') }}</dd>
                </div>
                <div class="rounded-2xl border border-stone-200/80 bg-stone-50/80 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                    <dt class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                        <i class="fa-regular fa-flag"></i>
                        Tahmini bitiş
                    </dt>
                    <dd class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $project->target_completion_date->translatedFormat('d M Y') }}</dd>
                </div>
                <div class="rounded-2xl border border-stone-200/80 bg-stone-50/80 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                    <dt class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                        <i class="fa-solid fa-signal"></i>
                        Durum
                    </dt>
                    <dd class="mt-1.5 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $project->status->label() }}</dd>
                </div>
            </dl>
        </header>

        <section class="mt-10 sm:mt-12" aria-labelledby="timeline-heading">
            <div class="flex items-end justify-between gap-3">
                <h2 id="timeline-heading" class="flex items-center gap-2 text-lg font-semibold">
                    <i class="fa-solid fa-timeline text-teal-600 dark:text-teal-400"></i>
                    Gelişmeler
                </h2>
                <p class="text-xs text-slate-400">En yeniden eskiye</p>
            </div>

            @if ($updates->isEmpty())
                <div class="mt-6 rounded-[1.5rem] border border-dashed border-stone-300 bg-white/70 px-5 py-10 text-center dark:border-white/15 dark:bg-slate-900/50">
                    <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-teal-50 text-teal-600 dark:bg-teal-400/10 dark:text-teal-300">
                        <i class="fa-regular fa-comments"></i>
                    </span>
                    <p class="mt-4 text-sm font-medium text-slate-700 dark:text-slate-200">Henüz paylaşılmış bir güncelleme yok.</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Yeni bir gelişme eklendiğinde burada görünecek.</p>
                </div>
            @else
                <div
                    id="timeline"
                    class="relative mt-6 space-y-4 sm:mt-8 sm:space-y-6 before:absolute before:top-2 before:bottom-2 before:left-[9px] before:w-px before:bg-stone-200 sm:before:left-[11px] dark:before:bg-white/10"
                    data-feed-url="{{ route('status.updates', $project->access_token) }}"
                    data-next-cursor="{{ $updates->nextCursor()?->encode() }}"
                    data-has-more="{{ $updates->hasMorePages() ? '1' : '0' }}"
                >
                    @include('status.partials.cards', ['updates' => $updates])
                </div>
                <div id="timeline-sentinel" class="h-8"></div>
                <p id="timeline-loading" class="hidden py-4 text-center text-sm text-slate-400">Yükleniyor…</p>
            @endif
        </section>
    </div>
@endsection
