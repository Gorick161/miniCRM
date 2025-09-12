@props(['label'=>null,'name'=>null,'hint'=>null])
<label class="block">
  @if($label)<span class="block text-sm text-ink-600 mb-1">{{ $label }}</span>@endif
  <input name="{{ $name }}" {{ $attributes->merge(['class'=>'w-full rounded-xl border border-ink-200 bg-white dark:bg-ink-900 dark:border-ink-700 px-3 py-2.5 focus-ring']) }}>
  @error($name)<span class="mt-1 block text-sm text-red-600">{{ $message }}</span>@enderror
  @if($hint)<span class="mt-1 block text-xs text-ink-500">{{ $hint }}</span>@endif
</label>
