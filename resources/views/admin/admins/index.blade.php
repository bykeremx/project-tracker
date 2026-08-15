@extends('layouts.admin')

@section('title', 'Yöneticiler')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="flex items-center gap-2 text-2xl font-semibold tracking-tight">
                <i class="fa-solid fa-user-shield text-teal-600 dark:text-teal-400"></i>
                Yöneticiler
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Panele giriş yetkisi olan hesaplar.</p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="btn-primary">
            <i class="fa-solid fa-user-plus"></i>
            Yeni yönetici
        </a>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-white/10">
            <thead class="bg-slate-50 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:bg-white/5 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-3">Ad</th>
                    <th class="px-5 py-3">E-posta</th>
                    <th class="px-5 py-3">Kayıt</th>
                    <th class="px-5 py-3 text-right">İşlem</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                @forelse ($admins as $admin)
                    <tr class="animate-fade-up transition hover:bg-slate-50 dark:hover:bg-white/5" style="animation-delay: {{ $loop->index * 40 }}ms">
                        <td class="px-5 py-4 font-medium">
                            {{ $admin->name }}
                            @if ($admin->is(auth()->user()))
                                <span class="ml-2 rounded-full bg-teal-50 px-2 py-0.5 text-xs text-teal-700 dark:bg-teal-500/10 dark:text-teal-200">Siz</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">{{ $admin->email }}</td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">{{ $admin->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <a href="{{ route('admin.admins.edit', $admin) }}" class="btn-ghost">
                                    <i class="fa-solid fa-pen"></i>
                                    Düzenle
                                </a>
                                @if ($admin->isNot(auth()->user()))
                                    <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" onsubmit="return confirm('Bu yönetici silinsin mi?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            Sil
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">Henüz yönetici yok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $admins->links() }}</div>
@endsection
