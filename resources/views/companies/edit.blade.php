<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Company bearbeiten</h2></x-slot>
    <div class="p-6">
        <form method="post" action="{{ route('companies.update',$company) }}" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf @method('PUT')
            @include('companies.partials.form', ['company'=>$company])
            <div class="flex gap-2">
                <x-primary-button>Aktualisieren</x-primary-button>
                <a href="{{ route('companies.show',$company) }}" class="px-4 py-2 border rounded-lg">Abbrechen</a>
            </div>
        </form>
    </div>
</x-app-layout>
