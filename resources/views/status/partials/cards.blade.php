@foreach ($updates as $update)
    <article class="animate-fade-up relative pl-10">
        <span class="absolute top-1 left-0 flex h-6 w-6 items-center justify-center rounded-full text-[10px] ring-4 {{ $update->status_type->iconClasses() }}">
            <i class="{{ $update->status_type->icon() }}"></i>
        </span>
        <div class="rounded-2xl border p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-md {{ $update->status_type->cardClasses() }}">
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="font-medium">{{ $update->title }}</h3>
                @include('partials.status-badge', ['status' => $update->status_type])
            </div>
            @if ($update->description)
                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $update->description }}</p>
            @endif
            <p class="mt-3 inline-flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                <i class="fa-regular fa-clock"></i>
                {{ $update->created_at->translatedFormat('d M Y H:i') }}
            </p>
        </div>
    </article>
@endforeach
