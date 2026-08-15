@php
    use App\Support\Money;
@endphp

@extends('layouts.admin')

@section('title', $current->translatedFormat('F Y').' tahsilatları')

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <div class="mb-3 flex flex-wrap items-center gap-2 text-sm">
                <a href="{{ route('admin.earnings.index', ['year' => $current->year]) }}" class="inline-flex items-center gap-1.5 text-teal-700 transition hover:text-teal-600 dark:text-teal-300">
                    <i class="fa-solid fa-coins"></i>
                    Tahsilatlar
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-slate-500 dark:text-slate-400">{{ $current->translatedFormat('F Y') }}</span>
            </div>
            <h2 class="text-2xl font-semibold tracking-tight">{{ $current->translatedFormat('F Y') }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Bu aya ait tahsilat satırları.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.earnings.show', [$previous->format('Y'), $previous->format('m')]) }}" class="btn-secondary">
                <i class="fa-solid fa-chevron-left"></i>
                {{ $previous->translatedFormat('M Y') }}
            </a>
            <a href="{{ route('admin.earnings.show', [$next->format('Y'), $next->format('m')]) }}" class="btn-secondary">
                {{ $next->translatedFormat('M Y') }}
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <div class="card mb-6 p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">Ay toplamı</p>
        <p class="mt-1 text-3xl font-semibold text-emerald-700 dark:text-emerald-300">{{ Money::format($total) }}</p>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-white/10">
            <thead class="bg-slate-50 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:bg-white/5 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-3">Tarih</th>
                    <th class="px-5 py-3">Proje</th>
                    <th class="px-5 py-3">Müşteri</th>
                    <th class="px-5 py-3">Not</th>
                    <th class="px-5 py-3 text-right">Tutar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                @forelse ($payments as $payment)
                    <tr class="animate-fade-up transition hover:bg-slate-50 dark:hover:bg-white/5" style="animation-delay: {{ $loop->index * 30 }}ms">
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">{{ $payment->paid_on->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-4 font-medium">
                            <a href="{{ route('admin.projects.show', $payment->project) }}" class="transition hover:text-teal-700 dark:hover:text-teal-300">
                                {{ $payment->project->title }}
                            </a>
                        </td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">
                            {{ $payment->project->client->name }}
                            @if ($payment->project->client->company_name)
                                <span class="block text-xs">{{ $payment->project->client->company_name }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">{{ $payment->note ?? '—' }}</td>
                        <td class="px-5 py-4 text-right font-medium text-emerald-700 dark:text-emerald-300">{{ Money::format($payment->amount) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">Bu ayda tahsilat yok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $payments->links() }}</div>
@endsection
