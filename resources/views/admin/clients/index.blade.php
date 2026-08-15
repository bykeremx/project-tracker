@extends('layouts.admin')

@section('title', 'Müşteriler')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight">Müşteriler</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Müşteri kayıtlarını oluşturun; projeye buradan geçin.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.projects.index') }}" class="btn-secondary">Tüm projeler</a>
            <a href="{{ route('admin.clients.create') }}" class="btn-primary">Yeni müşteri</a>
        </div>
    </div>

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-white/10">
            <thead class="bg-slate-50 text-left text-xs font-medium uppercase tracking-wide text-slate-500 dark:bg-white/5 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-3">Ad</th>
                    <th class="px-5 py-3">E-posta</th>
                    <th class="px-5 py-3">Firma</th>
                    <th class="px-5 py-3">Projeler</th>
                    <th class="px-5 py-3 text-right">İşlem</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                @forelse ($clients as $client)
                    @php $latestProject = $client->projects->first(); @endphp
                    <tr class="animate-fade-up transition hover:bg-slate-50 dark:hover:bg-white/5" style="animation-delay: {{ $loop->index * 40 }}ms">
                        <td class="px-5 py-4 font-medium">{{ $client->name }}</td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">{{ $client->email ?? '—' }}</td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">{{ $client->company_name ?? '—' }}</td>
                        <td class="px-5 py-4">{{ $client->projects_count }}</td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                @if ($latestProject)
                                    <a href="{{ route('admin.projects.show', $latestProject) }}" class="btn-primary">
                                        Projeye git
                                    </a>
                                    @if ($client->projects_count > 1)
                                        <a href="{{ route('admin.projects.index', ['client_id' => $client->id]) }}" class="btn-secondary">
                                            Tümü
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('admin.projects.create', ['client_id' => $client->id]) }}" class="btn-primary">
                                        Proje oluştur
                                    </a>
                                @endif
                                <a href="{{ route('admin.clients.edit', $client) }}" class="btn-ghost">Düzenle</a>
                                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Bu müşteri ve tüm projeleri silinsin mi?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Sil</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">Henüz müşteri yok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $clients->links() }}</div>
@endsection
