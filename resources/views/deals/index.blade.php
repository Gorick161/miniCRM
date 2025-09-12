<x-slot name="header">
    <h2 class="text-xl font-semibold">Deals {{ $pipeline ? '– '.$pipeline->name : '' }}</h2>
</x-slot>

@if(!$pipeline)
  <x-card class="mb-4">
    <div class="muted">Noch keine Pipeline angelegt. Lege eine Pipeline und Stages im Seeder an.</div>
  </x-card>
@endif
<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold">Deals</h2>
  </x-slot>

  <div class="p-0">
    {{-- SortableJS (für Drag&Drop). Wenn du kein Drag&Drop willst, kannst du das <script> weglassen. --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      @foreach($stages as $stage)
        <div class="card">
          <div class="card-p">
            <div class="flex items-center justify-between mb-3">
              <h3 class="font-semibold">{{ $stage->name }}</h3>
              <x-badge tone="slate">{{ $stage->deals->count() }}</x-badge>
            </div>

            <div class="space-y-3 min-h-10"
                 id="stage-{{ $stage->id }}"
                 data-stage-id="{{ $stage->id }}">
              @foreach($stage->deals as $deal)
                <div class="rounded-xl border border-ink-200 dark:border-ink-700 p-3 bg-white dark:bg-ink-900"
                     data-deal-id="{{ $deal->id }}">
                  <div class="font-medium">{{ $deal->title }}</div>
                  <div class="text-sm muted">{{ optional($deal->company)->name ?? '—' }}</div>
                  <div class="text-sm mt-1">{{ number_format($deal->value_cents/100,2,',','.') }} {{ $deal->currency }}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Drag&Drop Initialisierung (nur wenn du das Reorder-Feature nutzen willst) --}}
    <script>
      // Braucht ein <meta name="csrf-token"> im <head> (ist bei Breeze standard).
      const tokenMeta = document.querySelector('meta[name="csrf-token"]');
      const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '';

      // Alle Spalten aktivieren
      document.querySelectorAll('[id^="stage-"]').forEach(listEl => {
        new Sortable(listEl, {
          group: 'deals',
          animation: 150,
          ghostClass: 'opacity-50',
          onAdd: saveOrder,
          onUpdate: saveOrder,
        });
      });

      async function saveOrder(evt) {
        const stageEl = evt.to; // Ziel-Spalte
        const stageId = stageEl.getAttribute('data-stage-id');
        const dealIds = Array.from(stageEl.querySelectorAll('[data-deal-id]'))
          .map(el => el.getAttribute('data-deal-id'));

        try {
          const res = await fetch("{{ route('deals.reorder') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrf,
              'Accept': 'application/json',
            },
            body: JSON.stringify({ stage_id: stageId, deal_ids: dealIds }),
          });
          if (!res.ok) throw new Error('Save failed');
        } catch (e) {
          console.error(e);
          alert('Konnte neue Reihenfolge nicht speichern.');
        }
      }
    </script>
  </div>
</x-app-layout>
