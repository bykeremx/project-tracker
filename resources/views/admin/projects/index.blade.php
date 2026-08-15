@extends('layouts.admin')

@section('title', 'Projeler')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight">Projeler</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Müşteri projelerini yönetin ve canlı bağlantıyı paylaşın.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.clients.index') }}" class="btn-secondary">Müşterilere dön</a>
            <a href="{{ route('admin.projects.create') }}" class="btn-primary">Yeni proje</a>
        </div>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-white/10">
            <thead class="bg-slate-50 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:bg-white/5 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-3">Proje</th>
                    <th class="px-5 py-3">Müşteri</th>
                    <th class="px-5 py-3">Durum</th>
                    <th class="px-5 py-3">Hedef bitiş</th>
                    <th class="px-5 py-3">Adımlar</th>
                    <th class="px-5 py-3 text-right">İşlem</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                @forelse ($projects as $project)
                    <tr class="animate-fade-up transition hover:bg-slate-50 dark:hover:bg-white/5" style="animation-delay: {{ $loop->index * 40 }}ms">
                        <td class="px-5 py-4 font-medium">
                            <a href="{{ route('admin.projects.show', $project) }}" class="transition hover:text-teal-700 dark:hover:text-teal-300">{{ $project->title }}</a>
                        </td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">
                            {{ $project->client->name }}
                            @if ($project->client->company_name)
                                <span class="block text-xs">{{ $project->client->company_name }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $project->status->badgeClasses() }}">
                                {{ $project->status->label() }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">{{ $project->target_completion_date->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-4">{{ $project->updates_count }}</td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <a href="{{ route('admin.projects.show', $project) }}" class="btn-primary">Aç</a>
                                <button type="button" data-copy-link="{{ $project->publicStatusUrl() }}" class="btn-secondary">
                                    Linki kopyala
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">Henüz proje yok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $projects->links() }}</div>
@endsection
