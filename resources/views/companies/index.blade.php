<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Companies</h2></x-slot>

    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <form method="get" class="w-full md:w-96">
                <input name="s" value="{{ request('s') }}" placeholder="Suche Name, Domain, Telefon"
                       class="w-full border rounded-lg px-3 py-2">
            </form>
            <a href="{{ route('companies.create') }}" class="ml-3 px-3 py-2 border rounded-lg">+ Company</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($companies as $company)
                <a href="{{ route('companies.show', $company) }}" class="block bg-white rounded-xl shadow p-4 hover:shadow-md">
                    <div class="font-semibold">{{ $company->name }}</div>
                    <div class="text-sm text-gray-600">{{ $company->domain ?? '—' }}</div>
                    <div class="mt-2 text-sm">
                        {{ $company->contacts_count }} Kontakte • {{ $company->deals_count }} Deals
                    </div>
                </a>
            @empty
                <div class="text-sm text-gray-500">Keine Companies gefunden.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $companies->links() }}</div>
    </div>
</x-app-layout>
