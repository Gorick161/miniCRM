@props(['message'])
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     class="mb-4 rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3">
  {{ $message }}
</div>
