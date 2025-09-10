<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Dashboard</h2>
    </x-slot>

    <div class="p-6 space-y-6">

        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-xl shadow p-4">
                <div class="text-sm text-gray-500">Companies</div>
                <div class="text-2xl font-semibold">{{ number_format($stats['companies']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <div class="text-sm text-gray-500">Contacts</div>
                <div class="text-2xl font-semibold">{{ number_format($stats['contacts']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <div class="text-sm text-gray-500">Deals (Open)</div>
                <div class="text-2xl font-semibold">{{ number_format($stats['deals_open']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <div class="text-sm text-gray-500">Deals (Won)</div>
                <div class="text-2xl font-semibold">{{ number_format($stats['deals_won']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <div class="text-sm text-gray-500">Pipeline Value (Open)</div>
                <div class="text-2xl font-semibold">
                    {{ number_format(($stats['value_open'] ?? 0)/100, 2, ',', '.') }} €
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <div class="text-sm text-gray-500">Revenue (Won)</div>
                <div class="text-2xl font-semibold">
                    {{ number_format(($stats['value_won'] ?? 0)/100, 2, ',', '.') }} €
                </div>
            </div>
        </div>

        {{-- Pipeline Übersicht --}}
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Pipeline: {{ $pipeline->name ?? '—' }}</h3>
                <a href="{{ route('deals.index') }}" class="text-blue-600 hover:underline text-sm">Zum Kanban</a>
            </div>

            @if($stages->count())
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @foreach($stages as $stage)
                        <div class="border rounded-lg p-4">
                            <div class="font-medium">{{ $stage->name }}</div>
                            <div class="text-sm text-gray-500">{{ $stage->deals_count }} Deals</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-sm text-gray-500">Noch keine Pipeline/Stages angelegt.</div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Letzte Deals --}}
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="font-semibold mb-3">Letzte Deals</h3>
                <div class="divide-y">
                    @forelse($recentDeals as $deal)
                        <div class="py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium">{{ $deal->title }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ optional($deal->company)->name ?? '—' }} • Stage: {{ optional($deal->stage)->name ?? '—' }}
                                    </div>
                                </div>
                                <div class="text-sm">
                                    {{ number_format($deal->value_cents/100,2,',','.') }} {{ $deal->currency }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 py-3">Keine Deals vorhanden.</div>
                    @endforelse
                </div>
            </div>

            {{-- Letzte Aktivitäten --}}
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="font-semibold mb-3">Letzte Aktivitäten</h3>
                <div class="divide-y">
                    @forelse($recentActivities as $a)
                        <div class="py-3">
                            <div class="text-xs text-gray-500">
                                {{ $a->type }} • {{ optional($a->happened_at)->format('d.m.Y H:i') ?? '—' }}
                                @if($a->user)
                                    • von {{ $a->user->name }}
                                @endif
                            </div>
                            <div class="mt-1 text-sm whitespace-pre-line">
                                {{ Str::limit($a->body ?? '—', 180) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 py-3">Noch keine Aktivitäten.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
