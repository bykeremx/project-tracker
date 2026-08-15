@extends('layouts.admin')

@section('title', 'Müşteriyi düzenle')

@section('content')
    <div class="mx-auto max-w-xl">
        <h2 class="text-2xl font-semibold tracking-tight">Müşteriyi düzenle</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $client->name }}</p>

        <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="card mt-8 space-y-5 p-6">
            @csrf
            @method('PUT')
            @include('admin.clients._form')
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.clients.index') }}" class="btn-secondary">Vazgeç</a>
                <button type="submit" class="btn-primary">Güncelle</button>
            </div>
        </form>
    </div>
@endsection
