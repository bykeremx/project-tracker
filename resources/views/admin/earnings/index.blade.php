@php
    use App\Support\Money;
@endphp

@extends('layouts.admin')

@section('title', $year.' tahsilatları')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="flex items-center gap-2 text-2xl font-semibold tracking-tight">
                <i class="fa-solid fa-coins text-teal-600 dark:text-teal-400"></i>
                Tahsilatlar
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Aylara tıklayarak o ayın tüm tahsilat satırlarını görürsünüz.</p>
        </div>
        <form method="GET" action="{{ route('admin.earnings.index') }}" class="flex items-center gap-2">
            <label for="year" class="text-sm text-slate-500 dark:text-slate-400">Yıl</label>
            <select id="year" name="year" class="form-input w-auto" onchange="this.form.submit()">
                @foreach ($years as $optionYear)
                    <option value="{{ $optionYear }}" @selected($optionYear === $year)>{{ $optionYear }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="card mb-6 p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $year }} yılı toplamı</p>
        <p class="mt-1 text-3xl font-semibold">{{ Money::format($yearTotal) }}</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($months as $row)
            <a href="{{ route('admin.earnings.show', [$row['year'], $row['month']]) }}" class="card-hover animate-fade-up p-5" style="animation-delay: {{ $loop->index * 30 }}ms">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-medium">{{ $row['label'] }}</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $row['count'] }} tahsilat</p>
                    </div>
                    <i class="fa-solid fa-chevron-right mt-1 text-xs text-slate-400"></i>
                </div>
                <p class="mt-4 text-2xl font-semibold {{ $row['total'] === '0.00' ? 'text-slate-400 dark:text-slate-500' : 'text-emerald-700 dark:text-emerald-300' }}">
                    {{ Money::format($row['total']) }}
                </p>
            </a>
        @endforeach
    </div>
@endsection
