<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Contact bearbeiten</h2></x-slot>
    <div class="p-6">
        <form method="post" action="{{ route('contacts.update',$contact) }}" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf @method('PUT')
            @include('contacts.partials.form', ['contact'=>$contact])
            <div class="flex gap-2">
                <x-primary-button>Aktualisieren</x-primary-button>
                <a href="{{ route('contacts.show',$contact) }}" class="px-4 py-2 border rounded-lg">Abbrechen</a>
            </div>
        </form>
    </div>
</x-app-layout>
