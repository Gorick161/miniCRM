<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Deals – {{ $pipeline->name ?? 'No Pipeline' }}</h2>
    </x-slot>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach($stages as $stage)
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold">{{ $stage->name }}</h3>
                        <span class="text-sm text-gray-500">{{ $stage->deals->count() }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($stage->deals as $deal)
                            <div class="rounded-lg border p-3">
                                <div class="font-medium">{{ $deal->title }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ optional($deal->company)->name ?? '—' }}
                                </div>
                                <div class="text-sm">
                                    {{ number_format($deal->value_cents/100,2,',','.') }} {{ $deal->currency }}
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">Keine Deals</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
