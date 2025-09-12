<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold">Companies</h2>
      <a href="{{ route('companies.create') }}"><x-btn>+ Company</x-btn></a>
    </div>
  </x-slot>

  <div class="mb-4">
    <form method="get" class="max-w-lg">
      <x-field name="s" label="Suche">
        <input name="s" value="{{ request('s') }}" class="w-full rounded-xl border border-ink-200 bg-white dark:bg-ink-900 dark:border-ink-700 px-3 py-2.5 focus-ring" placeholder="Name, Domain, Telefon" />
      </x-field>
    </form>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @forelse($companies as $company)
      <a href="{{ route('companies.show', $company) }}" class="card">
        <div class="card-p">
          <div class="flex items-start justify-between">
            <div class="font-semibold">{{ $company->name }}</div>
            <x-badge tone="blue">{{ $company->deals_count }} Deals</x-badge>
          </div>
          <div class="mt-1 text-sm muted">{{ $company->domain ?? '—' }}</div>
          <div class="mt-3 text-sm">{{ $company->contacts_count }} Kontakte</div>
        </div>
      </a>
    @empty
      <x-card><div class="muted">Keine Companies gefunden.</div></x-card>
    @endforelse
  </div>

  <div class="mt-6">{{ $companies->links() }}</div>
</x-app-layout>
