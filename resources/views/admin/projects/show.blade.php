@php
    use App\Enums\UpdateStatusType;
    use App\Support\Money;
@endphp

@extends('layouts.admin')

@section('title', $project->title)

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <div class="mb-3 flex flex-wrap items-center gap-2 text-sm">
                <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center gap-1.5 text-teal-700 transition hover:text-teal-600 dark:text-teal-300">
                    <i class="fa-solid fa-users"></i>
                    Müşteriler
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center gap-1.5 text-teal-700 transition hover:text-teal-600 dark:text-teal-300">
                    <i class="fa-solid fa-diagram-project"></i>
                    Projeler
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-slate-500 dark:text-slate-400">{{ $project->title }}</span>
            </div>
            <p class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <i class="fa-solid fa-building"></i>
                {{ $project->client->name }}{{ $project->client->company_name ? ' · '.$project->client->company_name : '' }}
            </p>
            <h2 class="mt-1 text-2xl font-semibold tracking-tight">{{ $project->title }}</h2>
            <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-slate-500 dark:text-slate-400">
                <span class="inline-flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar"></i>
                    Başlangıç: {{ $project->start_date->translatedFormat('d M Y') }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <i class="fa-regular fa-flag"></i>
                    Hedef: {{ $project->target_completion_date->translatedFormat('d M Y') }}
                </span>
                @if ($project->actual_completion_date)
                    <span class="inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-flag-checkered"></i>
                        Gerçek bitiş: {{ $project->actual_completion_date->translatedFormat('d M Y') }}
                    </span>
                @endif
                @include('partials.status-badge', ['status' => $project->status])
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.clients.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Müşterilere dön
            </a>
            <button type="button" data-copy-link="{{ $project->publicStatusUrl() }}" class="btn-primary">
                <i class="fa-solid fa-link"></i>
                <span data-copy-text>Canlı linki kopyala</span>
            </button>
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn-secondary">
                <i class="fa-solid fa-pen"></i>
                Düzenle
            </a>
            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Proje silinsin mi?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fa-solid fa-trash"></i>
                    Sil
                </button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-1">
            <section class="card animate-fade-up p-5">
                <h3 class="flex items-center gap-2 font-medium">
                    <i class="fa-solid fa-toggle-on text-teal-600 dark:text-teal-400"></i>
                    Durumu değiştir
                </h3>
                <form method="POST" action="{{ route('admin.projects.status', $project) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-input">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected($project->status === $status)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary w-full">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Durumu kaydet
                    </button>
                </form>
            </section>

            <section class="card animate-fade-up p-5" style="animation-delay: 60ms">
                <h3 class="flex items-center gap-2 font-medium">
                    <i class="fa-solid fa-wallet text-teal-600 dark:text-teal-400"></i>
                    Bütçe ve tahsilat
                </h3>
                <dl class="mt-4 grid grid-cols-3 gap-3 text-sm">
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-white/5">
                        <dt class="text-xs text-slate-500 dark:text-slate-400">Anlaşılan</dt>
                        <dd class="mt-1 font-semibold">{{ Money::format($project->agreed_budget) }}</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-white/5">
                        <dt class="text-xs text-slate-500 dark:text-slate-400">Tahsil</dt>
                        <dd class="mt-1 font-semibold text-emerald-700 dark:text-emerald-300">{{ Money::format($project->collectedAmount()) }}</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-white/5">
                        <dt class="text-xs text-slate-500 dark:text-slate-400">Kalan</dt>
                        <dd class="mt-1 font-semibold">{{ Money::format($project->remainingAmount()) }}</dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('admin.projects.payments.store', $project) }}" class="mt-5 space-y-3">
                    @csrf
                    <div>
                        <label for="amount" class="mb-1.5 block text-sm">Tutar (TL)</label>
                        <input id="amount" name="amount" type="number" min="0.01" step="0.01" required value="{{ old('amount') }}" class="form-input">
                    </div>
                    <div>
                        <label for="paid_on" class="mb-1.5 block text-sm">Tarih</label>
                        <input id="paid_on" name="paid_on" type="date" required value="{{ old('paid_on', now()->toDateString()) }}" class="form-input">
                    </div>
                    <div>
                        <label for="note" class="mb-1.5 block text-sm">Not</label>
                        <input id="note" name="note" type="text" value="{{ old('note') }}" class="form-input" placeholder="Kapora, taksit…">
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <i class="fa-solid fa-plus"></i>
                        Tahsilat ekle
                    </button>
                </form>

                <ul class="mt-5 divide-y divide-slate-100 dark:divide-white/10">
                    @forelse ($project->payments as $payment)
                        <li class="flex items-start justify-between gap-3 py-3 text-sm">
                            <div>
                                <p class="font-medium">{{ Money::format($payment->amount) }}</p>
                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $payment->paid_on->translatedFormat('d M Y') }}
                                    @if ($payment->note)
                                        · {{ $payment->note }}
                                    @endif
                                </p>
                            </div>
                            <form method="POST" action="{{ route('admin.projects.payments.destroy', [$project, $payment]) }}" onsubmit="return confirm('Bu tahsilat silinsin mi?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger px-2 py-1 text-xs">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </li>
                    @empty
                        <li class="py-3 text-sm text-slate-500 dark:text-slate-400">Henüz tahsilat yok.</li>
                    @endforelse
                </ul>
            </section>

            <section class="card animate-fade-up p-5" style="animation-delay: 80ms">
                <h3 class="flex items-center gap-2 font-medium">
                    <i class="fa-solid fa-plus text-teal-600 dark:text-teal-400"></i>
                    Yeni adım ekle
                </h3>
                <form method="POST" action="{{ route('admin.projects.updates.store', $project) }}" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label for="title" class="mb-1.5 block text-sm">Başlık</label>
                        <input id="title" name="title" type="text" required value="{{ old('title') }}" class="form-input">
                    </div>
                    <div>
                        <label for="description" class="mb-1.5 block text-sm">Açıklama</label>
                        <textarea id="description" name="description" rows="4" class="form-input">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label for="status_type" class="mb-1.5 block text-sm">Durum tipi</label>
                        <select id="status_type" name="status_type" class="form-input">
                            @foreach (UpdateStatusType::cases() as $type)
                                <option value="{{ $type->value }}" @selected(old('status_type', 'completed') === $type->value)>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                        <input type="checkbox" name="is_public" value="1" @checked(old('is_public', true)) class="rounded border-slate-300 text-teal-600 dark:border-white/20 dark:bg-white/5">
                        <i class="fa-solid fa-eye"></i>
                        Müşteri görebilsin
                    </label>
                    <button type="submit" class="btn-primary w-full">
                        <i class="fa-solid fa-plus"></i>
                        Adımı kaydet
                    </button>
                </form>
            </section>
        </div>

        <section class="card animate-fade-up p-5 xl:col-span-2" style="animation-delay: 120ms">
            <h3 class="flex items-center gap-2 font-medium">
                <i class="fa-solid fa-timeline text-teal-600 dark:text-teal-400"></i>
                Zaman çizelgesi
            </h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Gizli notlar kilit ikonu ile işaretlidir; müşteri bunları görmez.</p>
            <div class="relative mt-8 space-y-6 before:absolute before:top-2 before:bottom-2 before:left-[11px] before:w-px before:bg-slate-200 dark:before:bg-white/15">
                @forelse ($updates as $update)
                    <article class="relative pl-10" style="animation-delay: {{ $loop->index * 70 }}ms">
                        <span class="absolute top-1 left-0 flex h-6 w-6 items-center justify-center rounded-full text-[10px] ring-4 {{ $update->status_type->iconClasses() }}">
                            <i class="{{ $update->status_type->icon() }}"></i>
                        </span>
                        <div class="rounded-xl border p-4 transition hover:-translate-y-0.5 hover:shadow-md {{ $update->status_type->cardClasses() }}">
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="font-medium">{{ $update->title }}</h4>
                                @include('partials.status-badge', ['status' => $update->status_type])
                                @unless ($update->is_public)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600 dark:bg-white/10 dark:text-slate-300">
                                        <i class="fa-solid fa-lock"></i>
                                        İç not
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-teal-50 px-2 py-0.5 text-xs text-teal-700 dark:bg-teal-500/10 dark:text-teal-200">
                                        <i class="fa-solid fa-eye"></i>
                                        Müşteriye açık
                                    </span>
                                @endunless
                            </div>
                            @if ($update->description)
                                <p class="mt-2 whitespace-pre-line text-sm text-slate-600 dark:text-slate-300">{{ $update->description }}</p>
                            @endif
                            <p class="mt-3 inline-flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                <i class="fa-regular fa-clock"></i>
                                {{ $update->created_at->translatedFormat('d M Y H:i') }}
                            </p>

                            <form method="POST" action="{{ route('admin.projects.updates.update', [$project, $update]) }}" class="mt-4 grid gap-3 border-t border-black/5 pt-4 dark:border-white/10 sm:grid-cols-[1fr_auto_auto] sm:items-end">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label for="status_type_{{ $update->id }}" class="mb-1.5 block text-xs text-slate-500 dark:text-slate-400">Durum tipi</label>
                                    <select id="status_type_{{ $update->id }}" name="status_type" class="form-input">
                                        @foreach (UpdateStatusType::cases() as $type)
                                            <option value="{{ $type->value }}" @selected($update->status_type === $type)>{{ $type->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="flex items-center gap-2 pb-2 text-sm text-slate-600 dark:text-slate-300">
                                    <input type="checkbox" name="is_public" value="1" @checked($update->is_public) class="rounded border-slate-300 text-teal-600 dark:border-white/20 dark:bg-white/5">
                                    <i class="fa-solid fa-eye"></i>
                                    Müşteri görebilsin
                                </label>
                                <button type="submit" class="btn-secondary">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    Kaydet
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="pl-10 text-sm text-slate-500 dark:text-slate-400">Henüz güncelleme yok.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
