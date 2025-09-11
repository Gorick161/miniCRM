@php($c = $company ?? null)
<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm text-gray-600 mb-1">Name *</label>
        <input name="name" value="{{ old('name', $c->name ?? '') }}" class="w-full border rounded-lg px-3 py-2" required>
        @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Domain</label>
        <input name="domain" value="{{ old('domain', $c->domain ?? '') }}" class="w-full border rounded-lg px-3 py-2">
        @error('domain')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Telefon</label>
        <input name="phone" value="{{ old('phone', $c->phone ?? '') }}" class="w-full border rounded-lg px-3 py-2">
        @error('phone')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm text-gray-600 mb-1">Notizen</label>
        <textarea name="notes" rows="4" class="w-full border rounded-lg px-3 py-2">{{ old('notes', $c->notes ?? '') }}</textarea>
        @error('notes')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
</div>
