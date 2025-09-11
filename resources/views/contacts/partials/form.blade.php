@php($c = $contact ?? null)
<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm text-gray-600 mb-1">Vorname *</label>
        <input name="first_name" value="{{ old('first_name', $c->first_name ?? '') }}" class="w-full border rounded-lg px-3 py-2" required>
        @error('first_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Nachname *</label>
        <input name="last_name" value="{{ old('last_name', $c->last_name ?? '') }}" class="w-full border rounded-lg px-3 py-2" required>
        @error('last_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">E-Mail</label>
        <input name="email" value="{{ old('email', $c->email ?? '') }}" class="w-full border rounded-lg px-3 py-2">
        @error('email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Telefon</label>
        <input name="phone" value="{{ old('phone', $c->phone ?? '') }}" class="w-full border rounded-lg px-3 py-2">
        @error('phone')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Position</label>
        <input name="position" value="{{ old('position', $c->position ?? '') }}" class="w-full border rounded-lg px-3 py-2">
        @error('position')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Company</label>
        <select name="company_id" class="w-full border rounded-lg px-3 py-2">
            <option value="">— Keine —</option>
            @foreach($companies ?? \App\Models\Company::orderBy('name')->get(['id','name']) as $co)
                <option value="{{ $co->id }}" @selected(old('company_id', $c->company_id ?? null) == $co->id)>
                    {{ $co->name }}
                </option>
            @endforeach
        </select>
        @error('company_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm text-gray-600 mb-1">Notizen</label>
        <textarea name="notes" rows="4" class="w-full border rounded-lg px-3 py-2">{{ old('notes', $c->notes ?? '') }}</textarea>
        @error('notes')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
    </div>
</div>
