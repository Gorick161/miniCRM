<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $company->name }}</h2></x-slot>

    <div class="p-6 space-y-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="grid md:grid-cols-3 gap-4">
                <div><div class="text-sm text-gray-500">Domain</div><div>{{ $company->domain ?? '—' }}</div></div>
                <div><div class="text-sm text-gray-500">Telefon</div><div>{{ $company->phone ?? '—' }}</div></div>
                <div><div class="text-sm text-gray-500">Owner</div><div>{{ optional($company->owner)->name ?? '—' }}</div></div>
            </div>
            @if($company->notes)
                <div class="mt-4">
                    <div class="text-sm text-gray-500">Notizen</div>
                    <div class="mt-1 whitespace-pre-line">{{ $company->notes }}</div>
                </div>
            @endif

            <div class="mt-4 flex gap-2">
                <a href="{{ route('companies.edit',$company) }}" class="px-3 py-2 border rounded-lg">Bearbeiten</a>
                <form method="post" action="{{ route('companies.destroy',$company) }}" onsubmit="return confirm('Wirklich löschen?')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-2 border rounded-lg text-red-600">Löschen</button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <h3 class="font-semibold mb-3">Kontakte ({{ $company->contacts->count() }})</h3>
            <div class="grid md:grid-cols-2 gap-3">
                @forelse($company->contacts as $c)
                    <a href="{{ route('contacts.show', $c) }}" class="border rounded-lg p-3 hover:bg-gray-50">
                        <div class="font-medium">{{ $c->full_name }}</div>
                        <div class="text-sm text-gray-600">{{ $c->email ?? '—' }}</div>
                    </a>
                @empty
                    <div class="text-sm text-gray-500">Keine Kontakte</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <h3 class="font-semibold mb-3">Deals ({{ $company->deals->count() }})</h3>
            <div class="grid md:grid-cols-2 gap-3">
                @forelse($company->deals as $d)
                    <div class="border rounded-lg p-3">
                        <div class="font-medium">{{ $d->title }}</div>
                        <div class="text-sm">{{ number_format($d->value_cents/100,2,',','.') }} {{ $d->currency }}</div>
                        <div class="text-xs text-gray-500 mt-1">Stage: {{ $d->stage->name ?? '—' }}</div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Keine Deals</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
