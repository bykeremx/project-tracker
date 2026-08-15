@extends('layouts.admin')

@section('title', 'Projeyi düzenle')

@section('content')
    <div class="mx-auto max-w-xl">
        <h2 class="text-2xl font-semibold tracking-tight">Projeyi düzenle</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $project->title }}</p>

        <form method="POST" action="{{ route('admin.projects.update', $project) }}" class="card mt-8 space-y-5 p-6">
            @csrf
            @method('PUT')
            @include('admin.projects._form')
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.projects.show', $project) }}" class="btn-secondary">Vazgeç</a>
                <button type="submit" class="btn-primary">Güncelle</button>
            </div>
        </form>
    </div>
@endsection
