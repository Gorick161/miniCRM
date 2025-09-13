{{-- Companies index — modern grid with toolbar --}}
@extends('layouts.app_pro')

@section('content')
  {{-- Page header --}}
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-6">
    <h1 class="text-2xl font-bold text-forest-100">Companies</h1>

    {{-- Toolbar: search + sort + per-page + create --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-2 sm:items-center">
      {{-- Keep query when changing selects --}}
      <input type="hidden" name="dir" value="{{ $dir }}">
      <div class="flex items-center gap-2">
        <input name="q" value="{{ $q }}" class="ui-input w-72" placeholder="Search company, website, industry…" />
        <select name="sort" class="ui-select" onchange="this.form.submit()">
          <option value="name"     @selected($sort==='name')>Name</option>
          <option value="created"  @selected($sort==='created')>Created</option>
          <option value="deals"    @selected($sort==='deals')>Deals</option>
          <option value="contacts" @selected($sort==='contacts')>Contacts</option>
        </select>
        <select name="per" class="ui-select" onchange="this.form.submit()">
          <option value="12" @selected($perPage===12)>12</option>
          <option value="24" @selected($perPage===24)>24</option>
          <option value="48" @selected($perPage===48)>48</option>
        </select>
        <button class="btn-outline" formaction="{{ url()->current() }}">Reset</button>
      </div>
    </form>

    {{-- Create company (opens modal) --}}
    <button class="btn" x-data x-on:click="$dispatch('open-company-modal')">New Company</button>
  </div>

  {{-- Grid of company cards --}}
  @if($companies->count())
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
      @foreach($companies as $c)
        <a href="{{ route('companies.show', $c) }}"
           class="panel p-4 group transition hover:-translate-y-0.5 hover:shadow-lg focus-ring">
          <div class="flex items-start justify-between gap-3">
            <div>
              <div class="text-lg font-semibold text-forest-50 group-hover:text-white">
                {{ $c->name }}
              </div>
              <div class="text-sm muted mt-0.5">
                {{ $c->industry ?: '—' }} @if($c->city) • {{ $c->city }} @endif
              </div>
            </div>
            {{-- Simple brand pill using first letter --}}
            <div class="h-9 w-9 rounded-full bg-forest-700/60 text-white grid place-items-center">
              {{ strtoupper(mb_substr($c->name,0,1)) }}
            </div>
          </div>

          {{-- Meta row --}}
          <div class="flex items-center gap-2 mt-4">
            <span class="px-2 py-1 rounded-md text-xs bg-forest-700/40 border border-forest-600/50">
              {{ $c->deals_count }} deals
            </span>
            <span class="px-2 py-1 rounded-md text-xs bg-forest-700/40 border border-forest-600/50">
              {{ $c->contacts_count }} contacts
            </span>
            @if($c->website)
              <span class="px-2 py-1 rounded-md text-xs bg-forest-700/40 border border-forest-600/50 truncate max-w-[10rem]">
                {{ preg_replace('/^https?:\/\//','',$c->website) }}
              </span>
            @endif
          </div>
        </a>
      @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">{{ $companies->links() }}</div>
  @else
    <x-empty title="No companies yet"
             subtitle="Create your first company to start tracking deals and contacts.">
      <button class="btn" x-data x-on:click="$dispatch('open-company-modal')">New Company</button>
    </x-empty>
  @endif

  {{-- Create Company Modal (Alpine-powered; posts to companies.store) --}}
  <div x-data="{ open:false }"
       x-on:open-company-modal.window="open=true"
       x-show="open" x-transition.opacity
       class="fixed inset-0 z-50 bg-black/50 grid place-items-center p-4">
    <div @click.outside="open=false"
         class="w-full max-w-lg panel p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">New Company</h3>
        <button class="btn-outline" @click="open=false">Close</button>
      </div>

      <form method="POST" action="{{ route('companies.store') }}" class="space-y-3">
        @csrf
        <div>
          <label class="ui-label">Name</label>
          <input name="name" required class="ui-input" placeholder="Acme GmbH" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="ui-label">Website</label>
            <input name="website" class="ui-input" placeholder="https://acme.example" />
          </div>
          <div>
            <label class="ui-label">Industry</label>
            <input name="industry" class="ui-input" placeholder="Software / Manufacturing" />
          </div>
        </div>
        <div>
          <label class="ui-label">City</label>
          <input name="city" class="ui-input" placeholder="Berlin" />
        </div>

        <div class="pt-2 flex items-center justify-end gap-2">
          <button type="button" class="btn-outline" @click="open=false">Cancel</button>
          <button class="btn">Create</button>
        </div>
      </form>
    </div>
  </div>
@endsection
