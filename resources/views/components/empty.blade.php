@props([
  'title' => 'Nothing here yet',
  'subtitle' => null,
  'icon' => '📭',
])

<div {{ $attributes->merge(['class' => 'panel p-10 text-center']) }}>
  <div class="mx-auto mb-4 h-12 w-12 rounded-2xl grid place-items-center bg-forest-700/40 border border-forest-600/50">
    <span class="text-xl">{{ $icon }}</span>
  </div>

  <h3 class="text-lg font-semibold text-forest-50">{{ $title }}</h3>

  @if($subtitle)
    <p class="mt-1 muted">{{ $subtitle }}</p>
  @endif

  @if(trim($slot))
    <div class="mt-4 flex items-center justify-center gap-2">
      {{ $slot }}
    </div>
  @endif
</div>
