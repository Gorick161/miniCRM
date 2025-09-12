<x-app-layout>
  <x-slot name="header"><h2 class="text-xl font-semibold">Dashboard</h2></x-slot>

  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    <x-kpi label="Companies" :value="number_format($stats['companies'])"/>
    <x-kpi label="Contacts" :value="number_format($stats['contacts'])"/>
    <x-kpi label="Deals (Open)" :value="number_format($stats['deals_open'])"/>
    <x-kpi label="Deals (Won)" :value="number_format($stats['deals_won'])"/>
    <x-kpi label="Pipeline Value (Open)" :value="number_format(($stats['value_open']??0)/100,2,',','.') . ' €'"/>
    <x-kpi label="Revenue (Won)" :value="number_format(($stats['value_won']??0)/100,2,',','.') . ' €'"/>
  </div>

  <x-card class="mt-6">
    <div class="flex items-center justify-between">
      <h3 class="font-semibold">Pipeline: {{ $pipeline->name ?? '—' }}</h3>
      <a href="{{ route('deals.index') }}" class="text-sm text-brand-600 hover:underline">Zum Kanban</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mt-4">
      @forelse($stages as $stage)
        <div class="rounded-xl border border-ink-200 dark:border-ink-700 p-4">
          <div class="font-medium">{{ $stage->name }}</div>
          <div class="text-sm muted">{{ $stage->deals_count }} Deals</div>
        </div>
      @empty
        <div class="muted">Noch keine Stages.</div>
      @endforelse
    </div>
  </x-card>
</x-app-layout>
