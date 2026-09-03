@foreach ($updates as $update)
    <article class="animate-fade-up relative pl-8 sm:pl-10">
        <span class="absolute top-1.5 left-0 z-10 flex h-5 w-5 items-center justify-center rounded-full bg-[#f4f0e8] dark:bg-slate-950 sm:top-1 sm:h-6 sm:w-6">
            <span class="flex h-full w-full items-center justify-center rounded-full text-[9px] sm:text-[10px] {{ $update->status_type->iconClasses() }}">
                <i class="{{ $update->status_type->icon() }}"></i>
            </span>
        </span>
        <div class="rounded-2xl border p-4 shadow-sm transition duration-300 sm:p-5 sm:hover:-translate-y-0.5 sm:hover:shadow-md {{ $update->status_type->cardClasses() }}">
            <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                <h3 class="text-base font-medium leading-snug">{{ $update->title }}</h3>
                @include('partials.status-badge', ['status' => $update->status_type])
            </div>
            @if ($update->description)
                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $update->description }}</p>
            @endif
            <p class="mt-3 inline-flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                <i class="fa-regular fa-clock"></i>
                <time datetime="{{ $update->created_at->toIso8601String() }}">{{ $update->created_at->translatedFormat('d M Y H:i') }}</time>
            </p>
        </div>
    </article>
@endforeach
