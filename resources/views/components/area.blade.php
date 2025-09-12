@props(['label'=>null,'name'=>null,'rows'=>4])
<label class="block">
  @if($label)<span class="block text-sm text-ink-600 mb-1">{{ $label }}</span>@endif
  <textarea name="{{ $name }}" rows="{{ $rows }}" {{ $attributes->merge(['class'=>'w-full rounded-xl border border-ink-200 bg-white dark:bg-ink-900 dark:border-ink-700 px-3 py-2.5 focus-ring']) }}>{{ $slot }}</textarea>
  @error($name)<span class="mt-1 block text-sm text-red-600">{{ $message }}</span>@enderror
</label>
