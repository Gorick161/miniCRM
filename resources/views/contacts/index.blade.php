<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Contacts</h2></x-slot>

    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <form method="get" class="w-full md:w-96">
                <input name="s" value="{{ request('s') }}" placeholder="Suche Name, E-Mail, Telefon"
                       class="w-full border rounded-lg px-3 py-2">
            </form>
            <a href="{{ route('contacts.create') }}" class="ml-3 px-3 py-2 border rounded-lg">+ Contact</a>
        </div>

        <div class="bg-white rounded-xl shadow divide-y">
            @forelse($contacts as $c)
                <a href="{{ route('contacts.show', $c) }}" class="block p-4 hover:bg-gray-50">
                    <div class="font-medium">{{ $c->full_name }}</div>
                    <div class="text-sm text-gray-600">
                        {{ $c->email ?? '—' }} • {{ $c->phone ?? '—' }} • {{ optional($c->company)->name ?? '—' }}
                    </div>
                </a>
            @empty
                <div class="p-4 text-sm text-gray-500">Keine Kontakte gefunden.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $contacts->links() }}</div>
    </div>
</x-app-layout>
