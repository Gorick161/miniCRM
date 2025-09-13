@props([
  'label' => '',
  'value' => '',
  'icon'  => null,
])

<div class="panel p-4 flex flex-col gap-2">
  <div class="flex items-center justify-between">
    <span class="text-sm muted">{{ $label }}</span>
    @if($icon)
      <span class="text-lg opacity-80">{{ $icon }}</span>
    @endif
  </div>
  <div class="text-2xl font-semibold">{{ $value }}</div>
</div>
