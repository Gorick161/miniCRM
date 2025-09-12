@props(['tone'=>'slate'])
@php
$tones = [
  'slate' => 'bg-ink-100 text-ink-700 dark:bg-ink-800 dark:text-ink-200',
  'green' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-200',
  'amber' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
  'blue'  => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
  'red'   => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
];
@endphp
<span {{ $attributes->merge(['class'=>'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium '.$tones[$tone]]) }}>
  {{ $slot }}
</span>
