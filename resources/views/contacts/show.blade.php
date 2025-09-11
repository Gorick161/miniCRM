<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $contact->full_name }}</h2></x-slot>

    <div class="p-6 space-y-6">
        <div class="bg-white rounded-xl shadow p-4 grid md:grid-cols-3 gap-4">
            <div><div class="text-sm text-gray-500">E-Mail</div><div>{{ $contact->email ?? '—' }}</div></div>
            <div><div class="text-sm text-gray-500">Telefon</div><div>{{ $contact->phone ?? '—' }}</div></div>
            <div>
                <div class="text-sm text-gray-500">Company</div>
                <div>
                    @if($contact->company)
                        <a class="text-blue-600 hover:underline" href="{{ route('companies.show',$contact->company) }}">{{ $contact->company->name }}</a>
                    @else — @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
