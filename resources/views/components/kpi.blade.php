@props(['label', 'value', 'icon' => null])

<div class="panel p-4">
    <div class="flex items-center justify-between gap-3">
        <div>
            <div class="text-sm muted">{{ $label }}</div>
            <div class="text-2xl font-semibold mt-1">{{ $value }}</div>
        </div>

        @if ($icon)
            <i data-lucide="{{ $icon }}" class="h-6 w-6 opacity-80"></i>
        @endif
    </div>
</div>
