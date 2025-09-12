@props(['variant' => 'primary', 'type' => 'button'])
@php
$base = 'inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-sm font-medium focus-ring transition';
$variants = [
  'primary' => 'bg-brand-600 text-white hover:bg-brand-700',
  'secondary' => 'border border-ink-200 text-ink-700 hover:bg-ink-100 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800',
  'danger' => 'bg-red-600 text-white hover:bg-red-700',
  'ghost' => 'text-ink-700 hover:bg-ink-100 dark:text-ink-200 dark:hover:bg-ink-800'
];
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => $base.' '.$variants[$variant]]) }}>
  {{ $slot }}
</button>
