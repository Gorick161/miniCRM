<x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    Dashboard
</x-nav-link>
<x-nav-link :href="route('deals.index')" :active="request()->routeIs('deals.*')">
    Deals
</x-nav-link>
<x-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">
    Companies
</x-nav-link>
<x-nav-link :href="route('contacts.index')" :active="request()->routeIs('contacts.*')">
    Contacts
</x-nav-link>
