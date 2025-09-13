@extends('layouts.app_pro')

@section('content')
    {{-- Header + Toolbar --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Deals</h1>
            <p class="muted text-sm">Drag items across stages. Reordering inside a column is supported.</p>
        </div>
        <div class="flex items-center gap-2">
            <input type="search" placeholder="Search deals…" class="ui-input w-72" x-data x-model.debounce.300ms="q">
            <select class="ui-select">
                <option>All pipelines</option>
                @foreach ($pipelines as $pl)
                    <option value="{{ $pl->id }}" @selected(optional($pipeline)->id === $pl->id)>{{ $pl->name }}</option>
                @endforeach
            </select>
            <button class="btn" x-data @click="$dispatch('open-new-deal')">New Deal</button>
        </div>
    </div>

    {{-- Kanban board --}}
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-4" x-data="kanbanBoard({
        updateStageUrl: '{{ route('deals.updateStage', 0) }}',
        reorderUrl: '{{ route('deals.reorder') }}'
    })">

        @foreach ($stages as $stage)
            <section class="panel p-0 flex flex-col min-h-[60vh] overflow-hidden">
                {{-- sticky stage header --}}
                <header
                    class="sticky top-0 z-10 bg-forest-50/90 dark:bg-forest-900/90 backdrop-blur
         px-4 py-3 border-b border-forest-200 dark:border-forest-700
         flex items-center justify-between rounded-t-xl">
                    <div class="font-semibold">{{ $stage->name }}</div>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-forest-500/10 border border-forest-500/30">
                        {{ $stage->deals->count() }}
                    </span>
                </header>

                {{-- drop area (flex + gap = no overlap) --}}
                <div id="stage-{{ $stage->id }}" data-stage-id="{{ $stage->id }}"
                    class="kanban-list flex flex-col gap-3 px-3 py-4 min-h-24" @dragover.prevent
                    @drop="onDrop($event, {{ $stage->id }})">

                    @foreach ($stage->deals->sortBy('position') as $deal)
                        <article
                            class="group relative rounded-xl border border-forest-200 dark:border-forest-700
         bg-white/95 dark:bg-forest-900/40 p-3 shadow-sm cursor-grab active:cursor-grabbing
         transform-gpu transition-transform transition-shadow duration-200 ease-out
         hover:scale-[1.02] hover:shadow-lg hover:border-forest-400/50 hover:z-10
         focus-within:scale-[1.02]"
                            draggable="true" data-deal-id="{{ $deal->id }}"
                            @dragstart="onDragStart($event, {{ $deal->id }})" @dragend="onDragEnd($event)">
                            <div class="flex items-start justify-between gap-2">
                                <div class="font-medium leading-tight">{{ $deal->title }}</div>
                                <span class="text-xs rounded-md px-2 py-0.5 bg-forest-500/10 border border-forest-500/30">
                                    {{ number_format($deal->value_cents / 100, 2, ',', '.') }} {{ $deal->currency }}
                                </span>
                            </div>
                            <div class="text-sm muted mt-0.5">
                                {{ optional($deal->company)->name ?? '—' }}
                            </div>
                        </article>
                    @endforeach

                    @if ($stage->deals->isEmpty())
                        <div class="muted text-sm border border-dashed rounded-xl p-3 text-center">
                            Drop deals here
                        </div>
                    @endif
                </div>
            </section>
        @endforeach
    </div>

    {{-- Add Deal Modal --}}
    <div x-data="{ open: false }" @open-new-deal.window="open=true" x-show="open" x-transition.opacity
        class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4" style="display:none">
        <div class="panel w-full max-w-lg p-5" @click.outside="open=false">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Create new deal</h3>
                <button class="p-2 rounded-lg hover:bg-forest-700/30" @click="open=false">✕</button>
            </div>

            <form method="POST" action="{{ route('deals.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-sm font-medium">Title</label>
                    <input name="title" required class="ui-input w-full" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium">Value</label>
                        <input type="number" step="0.01" name="value" class="ui-input w-full" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Currency</label>
                        <input name="currency" value="EUR" class="ui-input w-full" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium">Company</label>
                        <select name="company_id" class="ui-select w-full">
                            <option value="">—</option>
                            @foreach ($companies as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Stage</label>
                        <select name="stage_id" class="ui-select w-full">
                            @foreach ($stages as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" class="btn-outline" @click="open=false">Cancel</button>
                    <button class="btn">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Kanban DnD logic --}}
    <script>
        function kanbanBoard(config) {
            // keep current drag id
            let draggingId = null;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            // tiny JSON helper
            const postJson = (url, data) =>
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

            return {
                onDragStart(e, id) {
                    draggingId = id;
                    e.dataTransfer.effectAllowed = 'move';
                    // subtle visual feedback
                    e.target.classList.add('ring-2', 'ring-forest-400');
                },
                onDragEnd(e) {
                    e.target.classList.remove('ring-2', 'ring-forest-400');
                },
                async onDrop(e, newStageId) {
                    e.preventDefault();
                    if (!draggingId) return;

                    // move card visually to end of the column
                    const dropZone = e.currentTarget;
                    const card = document.querySelector(`[data-deal-id="${draggingId}"]`);
                    if (dropZone && card) dropZone.appendChild(card);

                    // 1) save the stage change
                    const url = config.updateStageUrl.replace('/0', '/' + draggingId);
                    await postJson(url, {
                        stage_id: newStageId
                    });

                    // 2) save the new order inside this column
                    const ids = Array.from(dropZone.querySelectorAll('[data-deal-id]'))
                        .map(n => +n.dataset.dealId);
                    await postJson(config.reorderUrl, {
                        stage_id: newStageId,
                        deal_ids: ids
                    });
                }
            };
        }
    </script>
@endsection
