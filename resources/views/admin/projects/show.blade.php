@php
    use App\Enums\UpdateStatusType;
@endphp

@extends('layouts.admin')

@section('title', $project->title)

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <div class="mb-3 flex flex-wrap items-center gap-2 text-sm">
                <a href="{{ route('admin.clients.index') }}" class="text-teal-700 transition hover:text-teal-600">Müşteriler</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.projects.index') }}" class="text-teal-700 transition hover:text-teal-600">Projeler</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">{{ $project->title }}</span>
            </div>
            <p class="text-sm text-slate-500">{{ $project->client->name }}{{ $project->client->company_name ? ' · '.$project->client->company_name : '' }}</p>
            <h2 class="mt-1 text-2xl font-semibold tracking-tight">{{ $project->title }}</h2>
            <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <span>Başlangıç: {{ $project->start_date->translatedFormat('d M Y') }}</span>
                <span>Hedef: {{ $project->target_completion_date->translatedFormat('d M Y') }}</span>
                @if ($project->actual_completion_date)
                    <span>Gerçek bitiş: {{ $project->actual_completion_date->translatedFormat('d M Y') }}</span>
                @endif
                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $project->status->badgeClasses() }}">
                    {{ $project->status->label() }}
                </span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.clients.index') }}" class="btn-secondary">Müşterilere dön</a>
            <button type="button" data-copy-link="{{ $project->publicStatusUrl() }}" class="btn-primary">
                Canlı linki kopyala
            </button>
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn-secondary">Düzenle</a>
            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Proje silinsin mi?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Sil</button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-1">
            <section class="card animate-fade-up p-5">
                <h3 class="font-medium">Durumu değiştir</h3>
                <form method="POST" action="{{ route('admin.projects.status', $project) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm transition focus:border-teal-300 focus:ring-2 focus:ring-teal-500/20 focus:outline-none">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected($project->status === $status)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:-translate-y-0.5 hover:bg-slate-800">
                        Durumu kaydet
                    </button>
                </form>
            </section>

            <section class="card animate-fade-up p-5" style="animation-delay: 80ms">
                <h3 class="font-medium">Yeni adım ekle</h3>
                <form method="POST" action="{{ route('admin.projects.updates.store', $project) }}" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label for="title" class="mb-1.5 block text-sm">Başlık</label>
                        <input id="title" name="title" type="text" required value="{{ old('title') }}"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm transition focus:border-teal-300 focus:ring-2 focus:ring-teal-500/20 focus:outline-none">
                    </div>
                    <div>
                        <label for="description" class="mb-1.5 block text-sm">Açıklama</label>
                        <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm transition focus:border-teal-300 focus:ring-2 focus:ring-teal-500/20 focus:outline-none">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label for="status_type" class="mb-1.5 block text-sm">Durum tipi</label>
                        <select id="status_type" name="status_type" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm transition focus:border-teal-300 focus:ring-2 focus:ring-teal-500/20 focus:outline-none">
                            @foreach (UpdateStatusType::cases() as $type)
                                <option value="{{ $type->value }}" @selected(old('status_type', 'completed') === $type->value)>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_public" value="1" @checked(old('is_public', true))>
                        Müşteri görebilsin
                    </label>
                    <button type="submit" class="btn-primary w-full">
                        Adımı kaydet
                    </button>
                </form>
            </section>
        </div>

        <section class="card animate-fade-up p-5 xl:col-span-2" style="animation-delay: 120ms">
            <h3 class="font-medium">Zaman çizelgesi</h3>
            <p class="mt-1 text-sm text-slate-500">Gizli notlar kilit ikonu ile işaretlidir; müşteri bunları görmez.</p>
            <div class="relative mt-8 space-y-6 before:absolute before:top-2 before:bottom-2 before:left-[11px] before:w-px before:bg-slate-200">
                @forelse ($updates as $update)
                    <article class="relative pl-10" style="animation-delay: {{ $loop->index * 70 }}ms">
                        <span class="absolute top-1 left-0 h-6 w-6 rounded-full ring-4 {{ $update->status_type->iconClasses() }}"></span>
                        <div class="rounded-xl border {{ $update->status_type->cardClasses() }} p-4 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="font-medium">{{ $update->title }}</h4>
                                <span class="text-xs text-slate-500">{{ $update->status_type->label() }}</span>
                                @unless ($update->is_public)
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">İç not</span>
                                @endunless
                            </div>
                            @if ($update->description)
                                <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $update->description }}</p>
                            @endif
                            <p class="mt-3 text-xs text-slate-400">{{ $update->created_at->translatedFormat('d M Y H:i') }}</p>
                        </div>
                    </article>
                @empty
                    <p class="pl-10 text-sm text-slate-500">Henüz güncelleme yok.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
