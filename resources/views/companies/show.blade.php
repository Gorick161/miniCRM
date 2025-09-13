@extends('layouts.app_pro')

@section('content')
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-6">
    <div>
      <h1 class="text-2xl font-bold text-forest-100">{{ $company->name }}</h1>
      <div class="muted">
        {{ $company->industry ?: '—' }} @if($company->city) • {{ $company->city }} @endif
      </div>
    </div>
    <div class="flex items-center gap-2">
      @if($company->website)
        <a class="btn-outline" href="{{ $company->website }}" target="_blank" rel="noopener">Website</a>
      @endif
      <a class="btn-outline" href="{{ route('companies.index') }}">Back to list</a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left: summary --}}
    <section class="panel p-4">
      <h3 class="font-semibold">Summary</h3>
      <div class="mt-3 grid grid-cols-2 gap-3">
        <div class="rounded-xl border border-forest-700/50 p-3 text-center">
          <div class="text-sm muted">Deals</div>
          <div class="text-xl font-semibold">{{ $company->deals_count }}</div>
        </div>
        <div class="rounded-xl border border-forest-700/50 p-3 text-center">
          <div class="text-sm muted">Contacts</div>
          <div class="text-xl font-semibold">{{ $company->contacts_count }}</div>
        </div>
      </div>
    </section>

    {{-- Recent deals --}}
    <section class="panel p-4 lg:col-span-2">
      <div class="flex items-center justify-between">
        <h3 class="font-semibold">Recent Deals</h3>
        <a href="{{ route('deals.index') }}" class="btn-outline text-sm">Open Kanban</a>
      </div>
      <div class="mt-4 grid gap-3 grid-cols-1 md:grid-cols-2">
        @forelse($company->deals as $d)
          <div class="rounded-xl border border-forest-700/50 p-3">
            <div class="font-medium truncate">{{ $d->title }}</div>
            <div class="text-sm muted mt-1">
              {{ number_format($d->value_cents/100,2,',','.') }} {{ $d->currency }}
            </div>
          </div>
        @empty
          <div class="muted">No recent deals.</div>
        @endforelse
      </div>
    </section>

    {{-- Recent contacts --}}
    <section class="panel p-4 lg:col-span-3">
      <h3 class="font-semibold">Recent Contacts</h3>
      <div class="mt-4 grid gap-3 grid-cols-1 md:grid-cols-3">
        @forelse($company->contacts as $p)
          <div class="rounded-xl border border-forest-700/50 p-3">
            <div class="font-medium">{{ $p->first_name }} {{ $p->last_name }}</div>
            <div class="text-sm muted truncate">{{ $p->email ?: '—' }}</div>
          </div>
        @empty
          <div class="muted">No recent contacts.</div>
        @endforelse
      </div>
    </section>
  </div>
@endsection
