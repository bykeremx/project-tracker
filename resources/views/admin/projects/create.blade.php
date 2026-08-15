@extends('layouts.admin')

@section('title', 'Yeni proje')

@section('content')
    <div class="mx-auto max-w-xl">
        <h2 class="text-2xl font-semibold tracking-tight">Yeni proje</h2>
        <p class="mt-1 text-sm text-slate-500">Erişim anahtarı kayıt sırasında otomatik üretilir.</p>

        @if ($clients->isEmpty())
            <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-900">
                Önce bir müşteri oluşturmalısınız.
                <a href="{{ route('admin.clients.create') }}" class="ml-1 font-medium underline">Müşteri ekle</a>
            </div>
        @else
            <form method="POST" action="{{ route('admin.projects.store') }}" class="card mt-8 space-y-5 p-6">
                @csrf
                @include('admin.projects._form')
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.projects.index') }}" class="btn-secondary">Vazgeç</a>
                    <button type="submit" class="btn-primary">Oluştur</button>
                </div>
            </form>
        @endif
    </div>
@endsection
