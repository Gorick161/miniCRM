{{-- resources/views/contacts/show.blade.php --}}
@extends('layouts.app_pro')

@section('content')
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">
        {{ $contact->first_name }} {{ $contact->last_name }}
      </h1>
      @if($contact->title)
        <p class="muted">{{ $contact->title }}</p>
      @endif
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('contacts.index') }}" class="btn-outline">Back</a>
      {{-- Optional: Edit/Delete später --}}
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <section class="panel p-6">
      <h3 class="font-semibold mb-3">Contact info</h3>
      <dl class="space-y-2 text-sm">
        <div class="flex justify-between border-b border-forest-800/50 pb-2">
          <dt class="muted">Email</dt>
          <dd>
            @if($contact->email)
              <a class="hover:underline" href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
            @else — @endif
          </dd>
        </div>
        <div class="flex justify-between border-b border-forest-800/50 pb-2">
          <dt class="muted">Phone</dt>
          <dd>{{ $contact->phone ?: '—' }}</dd>
        </div>
        <div class="flex justify-between">
          <dt class="muted">Company</dt>
          <dd>
            @if($contact->company)
              <a class="hover:underline" href="{{ route('companies.show',$contact->company_id) }}">
                {{ $contact->company->name }}
              </a>
            @else — @endif
          </dd>
        </div>
      </dl>
    </section>

    <section class="panel p-6">
      <h3 class="font-semibold mb-3">Activity (coming soon)</h3>
      <p class="muted">Hook tasks, notes and emails here later.</p>
    </section>
  </div>
@endsection
