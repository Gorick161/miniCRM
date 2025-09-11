<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Company anlegen</h2></x-slot>
    <div class="p-6">
        <form method="post" action="{{ route('companies.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf
            @include('companies.partials.form')
            <div class="flex gap-2">
                <x-primary-button>Speichern</x-primary-button>
                <a href="{{ route('companies.index') }}" class="px-4 py-2 border rounded-lg">Abbrechen</a>
            </div>
        </form>
    </div>
</x-app-layout>
