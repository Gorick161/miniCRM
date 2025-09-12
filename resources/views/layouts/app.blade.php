@php $dark = session('dark') @endphp
<!DOCTYPE html>
<html x-data
      x-init="document.documentElement.classList.toggle('dark', localStorage.theme === 'dark')"
      lang="de">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MiniCRM') }}</title>
    <!-- Inter Font optional -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-dvh">
    <div class="border-b bg-white/70 dark:bg-ink-900/70 backdrop-blur supports-backdrop-blur:backdrop-blur">
        <div class="container-app flex items-center gap-4 py-3">
            <a href="{{ route('dashboard') }}" class="font-semibold text-ink-800 dark:text-white">MiniCRM</a>
            <nav class="ml-6 hidden md:flex items-center gap-2">
                <x-nav :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav>
                <x-nav :href="route('deals.index')" :active="request()->routeIs('deals.*')">Deals</x-nav>
                <x-nav :href="route('companies.index')" :active="request()->routeIs('companies.*')">Companies</x-nav>
                <x-nav :href="route('contacts.index')" :active="request()->routeIs('contacts.*')">Contacts</x-nav>
            </nav>
            <div class="ml-auto flex items-center gap-2">
                <button class="px-3 py-2 rounded-lg hover:bg-ink-100 dark:hover:bg-ink-800 focus-ring"
                        x-on:click="const d=document.documentElement; const isDark=d.classList.toggle('dark'); localStorage.theme = isDark ? 'dark':'light'">
                    <span class="sr-only">Theme umschalten</span>
                    🌙
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-3 py-2 rounded-lg border hover:bg-ink-100 dark:hover:bg-ink-800 focus-ring">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <main class="container-app py-6">
        @if (session('status'))
            <x-toast :message="session('status')" />
        @endif

        {{ $slot }}
    </main>
</body>
</html>
