@props(['label','value','sub'=>null])
<x-card class="shadow-card">
  <div class="text-sm muted">{{ $label }}</div>
  <div class="mt-1 text-2xl font-semibold">{{ $value }}</div>
  @if($sub)<div class="mt-1 text-xs muted">{{ $sub }}</div>@endif
</x-card>
