<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $status->badgeClasses() }}">
    <i class="{{ $status->icon() }}"></i>
    {{ $status->label() }}
</span>
