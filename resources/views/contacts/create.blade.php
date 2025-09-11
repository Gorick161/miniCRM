<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Contact anlegen</h2></x-slot>
    <div class="p-6">
        <form method="post" action="{{ route('contacts.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf
            @include('contacts.partials.form')
            <div class="flex gap-2">
                <x-primary-button>Speichern</x-primary-button>
                <a href="{{ route('contacts.index') }}" class="px-4 py-2 border rounded-lg">Abbrechen</a>
            </div>
        </form>
    </div>
</x-app-layout>
