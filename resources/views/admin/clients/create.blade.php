@extends('layouts.admin')

@section('title', 'Yeni müşteri')

@section('content')
    <div class="mx-auto max-w-xl">
        <h2 class="flex items-center gap-2 text-2xl font-semibold tracking-tight">
            <i class="fa-solid fa-user-plus text-teal-600 dark:text-teal-400"></i>
            Yeni müşteri
        </h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ad zorunludur; e-posta ve firma isteğe bağlıdır.</p>

        <form method="POST" action="{{ route('admin.clients.store') }}" class="card mt-8 space-y-5 p-6">
            @csrf
            @include('admin.clients._form')
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.clients.index') }}" class="btn-secondary">
                    <i class="fa-solid fa-xmark"></i>
                    Vazgeç
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Kaydet
                </button>
            </div>
        </form>
    </div>
@endsection
