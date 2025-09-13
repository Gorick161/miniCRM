{{-- resources/views/contacts/index.blade.php --}}
@extends('layouts.app_pro')

@section('content')
  {{-- Page header / toolbar --}}
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
    <div>
      <h1 class="text-2xl font-bold">Contacts</h1>
      <p class="muted">Total: {{ number_format($stats['total']) }}</p>
    </div>

    <form method="get" class="flex flex-wrap items-center gap-2">
      <input name="q" value="{{ $q }}" placeholder="Search name, email, phone…" class="ui-input w-64" />
      <select name="sort" class="ui-select">
        <option value="name"   @selected($sort==='name')>Sort by Name</option>
        <option value="company"@selected($sort==='company')>Sort by Company</option>
        <option value="email"  @selected($sort==='email')>Sort by Email</option>
        <option value="recent" @selected($sort==='recent')>Newest first</option>
      </select>
      <select name="perPage" class="ui-select">
        <option value="12" @selected($perPage==12)>12</option>
        <option value="24" @selected($perPage==24)>24</option>
        <option value="50" @selected($perPage==50)>50</option>
      </select>
      <button class="btn">Apply</button>

      <button type="button" class="btn-outline" x-data @click="$dispatch('open-contact-modal')">
        New Contact
      </button>
    </form>
  </div>

  @if($contacts->count() === 0)
    <x-empty title="No contacts yet" subtitle="Create your first contact and link it with a company.">
      <button class="btn" x-data @click="$dispatch('open-contact-modal')">New Contact</button>
    </x-empty>
  @else
    {{-- Responsive table card --}}
    <div class="panel p-0 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-forest-900/40 border-b border-forest-700/60">
            <tr>
              <th class="text-left px-4 py-3 font-semibold">Name</th>
              <th class="text-left px-4 py-3 font-semibold">Company</th>
              <th class="text-left px-4 py-3 font-semibold">Email</th>
              <th class="text-left px-4 py-3 font-semibold">Phone</th>
              <th class="text-right px-4 py-3 font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($contacts as $c)
              <tr class="border-b border-forest-800/50 hover:bg-forest-800/20">
                <td class="px-4 py-3">
                  <a href="{{ route('contacts.show',$c) }}" class="font-medium hover:underline">
                    {{ $c->first_name }} {{ $c->last_name }}
                  </a>
                  @if($c->title)
                    <div class="muted">{{ $c->title }}</div>
                  @endif
                </td>
                <td class="px-4 py-3">
                  @if($c->company)
                    <a href="{{ route('companies.show',$c->company_id) }}" class="hover:underline">
                      {{ $c->company->name }}
                    </a>
                  @else
                    <span class="muted">—</span>
                  @endif
                </td>
                <td class="px-4 py-3">
                  @if($c->email)
                    <a href="mailto:{{ $c->email }}" class="hover:underline">{{ $c->email }}</a>
                  @else
                    <span class="muted">—</span>
                  @endif
                </td>
                <td class="px-4 py-3">{{ $c->phone ?: '—' }}</td>
                <td class="px-4 py-3 text-right">
                  <a class="btn-outline text-xs" href="{{ route('contacts.show',$c) }}">Open</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="px-4 py-3 border-t border-forest-800/50">
        {{ $contacts->links() }}
      </div>
    </div>
  @endif

  {{-- Create contact modal (Alpine) --}}
  <div x-data="{ open:false }"
       x-on:open-contact-modal.window="open=true"
       x-show="open"
       x-transition.opacity
       class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4"
       style="display:none">
    <div class="panel w-full max-w-lg p-6 relative">
      <button class="absolute top-3 right-3 px-2 py-1 rounded-lg hover:bg-forest-700/40"
              @click="open=false">✕</button>

      <h3 class="text-lg font-semibold mb-4">New Contact</h3>

      <form method="POST" action="{{ route('contacts.store') }}" class="space-y-3">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="label">First name</label>
            <input name="first_name" required class="ui-input">
          </div>
          <div>
            <label class="label">Last name</label>
            <input name="last_name" required class="ui-input">
          </div>
        </div>

        <div>
          <label class="label">Title (optional)</label>
          <input name="title" class="ui-input">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="label">Email</label>
            <input type="email" name="email" class="ui-input">
          </div>
          <div>
            <label class="label">Phone</label>
            <input name="phone" class="ui-input">
          </div>
        </div>

        <div>
          <label class="label">Company</label>
          <select name="company_id" class="ui-select w-full">
            <option value="">— none —</option>
            @foreach($companies as $co)
              <option value="{{ $co->id }}">{{ $co->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="pt-2 flex items-center justify-end gap-2">
          <button type="button" class="btn-outline" @click="open=false">Cancel</button>
          <button class="btn">Create</button>
        </div>
      </form>
    </div>
  </div>
@endsection
