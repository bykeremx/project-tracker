<div>
    <label for="client_id" class="mb-1.5 block text-sm font-medium">Müşteri</label>
    <select id="client_id" name="client_id" required class="form-input">
        <option value="">Seçin</option>
        @foreach ($clients as $clientOption)
            <option value="{{ $clientOption->id }}" @selected((string) old('client_id', $project->client_id ?? request('client_id')) === (string) $clientOption->id)>
                {{ $clientOption->name }}{{ $clientOption->company_name ? ' · '.$clientOption->company_name : '' }}
            </option>
        @endforeach
    </select>
</div>
<div>
    <label for="title" class="mb-1.5 block text-sm font-medium">Proje başlığı</label>
    <input id="title" name="title" type="text" value="{{ old('title', $project->title ?? '') }}" required class="form-input">
</div>
<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="start_date" class="mb-1.5 block text-sm font-medium">Başlangıç</label>
        <input id="start_date" name="start_date" type="date" value="{{ old('start_date', isset($project) ? $project->start_date->format('Y-m-d') : '') }}" required class="form-input">
    </div>
    <div>
        <label for="target_completion_date" class="mb-1.5 block text-sm font-medium">Tahmini bitiş</label>
        <input id="target_completion_date" name="target_completion_date" type="date" value="{{ old('target_completion_date', isset($project) ? $project->target_completion_date->format('Y-m-d') : '') }}" required class="form-input">
    </div>
</div>
<div>
    <label for="agreed_budget" class="mb-1.5 block text-sm font-medium">Anlaşılan bütçe (TL)</label>
    <input id="agreed_budget" name="agreed_budget" type="number" min="0" step="0.01" value="{{ old('agreed_budget', isset($project) ? $project->agreed_budget : '') }}" class="form-input" placeholder="Örn. 45000">
    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Boş bırakılabilir. Tahsilatlar bu tutardan düşülür.</p>
</div>
