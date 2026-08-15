@extends('layouts.admin')

@section('title', 'Yeni müşteri')

@section('content')
    <div class="mx-auto max-w-xl">
        <h2 class="text-2xl font-semibold tracking-tight">Yeni müşteri</h2>
        <p class="mt-1 text-sm text-slate-500">Ad zorunludur; e-posta ve firma isteğe bağlıdır.</p>

        <form method="POST" action="{{ route('admin.clients.store') }}" class="card mt-8 space-y-5 p-6">
            @csrf
            @include('admin.clients._form')
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.clients.index') }}" class="btn-secondary">Vazgeç</a>
                <button type="submit" class="btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
@endsection
