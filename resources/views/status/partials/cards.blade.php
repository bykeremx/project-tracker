@foreach ($updates as $update)
    <article class="animate-fade-up relative pl-10">
        <span class="absolute top-1 left-0 h-6 w-6 rounded-full ring-4 {{ $update->status_type->iconClasses() }}"></span>
        <div class="rounded-2xl border {{ $update->status_type->cardClasses() }} p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="font-medium">{{ $update->title }}</h3>
                <span class="text-xs text-slate-500">{{ $update->status_type->label() }}</span>
            </div>
            @if ($update->description)
                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $update->description }}</p>
            @endif
            <p class="mt-3 text-xs text-slate-400">{{ $update->created_at->translatedFormat('d M Y H:i') }}</p>
        </div>
    </article>
@endforeach
