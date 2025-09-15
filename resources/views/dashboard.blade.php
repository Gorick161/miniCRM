@php
    $stats = $stats ?? [
        'companies' => 0,
        'contacts' => 0,
        'deals_open' => 0,
        'deals_won' => 0,
        'value_open' => 0,
        'value_won' => 0,
    ];
    $pipeline = $pipeline ?? null;
    $stages = $stages ?? collect();
    $fmt = fn($cents) => number_format(($cents ?? 0) / 100, 2, ',', '.') . ' €';
@endphp


@extends('layouts.app_pro') {{-- use the new sidebar+topbar layout --}}

@section('content')
    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <div class="flex items-center gap-2">
            <select class="ui-select">
                <option>Sales Pipeline</option>
            </select>
            <select class="ui-select">
                <option>This month</option>
                <option>Last month</option>
                <option>This quarter</option>
            </select>
            <button class="btn-outline">Download report</button>
            <button class="btn">Submit</button>
        </div>
    </div>

    {{-- KPI row --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        <x-kpi label="Companies" :value="number_format($stats['companies'])" icon="building-2" />
        <x-kpi label="Contacts" :value="number_format($stats['contacts'])" icon="users" />
        <x-kpi label="Deals (Open)" :value="number_format($stats['deals_open'])" icon="handshake" />
        <x-kpi label="Deals (Won)" :value="number_format($stats['deals_won'])" icon="trophy" />
        <x-kpi label="Pipeline Value" :value="$fmt($stats['value_open'] ?? 0)" icon="line-chart" />
        <x-kpi label="Revenue (Won)" :value="$fmt($stats['value_won'] ?? 0)" icon="wallet" />
    </div>


    {{-- Charts (placeholders) --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">
        <section class="panel p-4">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">Sales Performance</h3>
                <button class="btn-outline text-xs px-3 py-1.5">Monthly</button>
            </div>
            <div
                class="mt-4 h-64 rounded-xl bg-white/80 dark:bg-white/5 border border-forest-300/60 dark:border-forest-700/60">
            </div>
        </section>
        <section class="panel p-4">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">Sales Analytics</h3>
                <button class="btn-outline text-xs px-3 py-1.5">Overview</button>
            </div>
            <div
                class="mt-4 h-64 rounded-xl bg-white/80 dark:bg-white/5 border border-forest-300/60 dark:border-forest-700/60">
            </div>
        </section>
    </div>

    {{-- Pipeline overview --}}
    <section class="panel p-4 mt-6">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">Pipeline: {{ $pipeline->name ?? '—' }}</h3>
            <a href="{{ route('deals.index') }}" class="btn-outline text-sm">Open Kanban</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-4">
            @forelse($stages as $stage)
                <div
                    class="p-4 rounded-xl border border-forest-200 dark:border-forest-700 bg-forest-50 dark:bg-forest-900/40">
                    <div class="font-medium">{{ $stage->name }}</div>
                    <div class="text-sm muted">{{ $stage->deals_count }} Deals</div>
                </div>
            @empty
                <div class="muted">No stages yet.</div>
            @endforelse
        </div>
    </section>
@endsection
