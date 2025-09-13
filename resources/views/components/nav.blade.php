@props(['href' => '#', 'active' => false])

<a href="{{ $href }}"
   @class([
     'px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 nav-underline transform-gpu hover:-translate-y-0.5',
     'nav-underline-active bg-brand-50 text-brand-700 dark:bg-ink-800 dark:text-brand-200' => $active,
     'text-ink-600 hover:bg-ink-100 dark:text-ink-300 dark:hover:bg-ink-800' => ! $active,
   ])>
  {{ $slot }}
</a>

