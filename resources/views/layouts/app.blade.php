@php $dark = session('dark') @endphp
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MiniCRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Dark-Mode sofort setzen (kein FOUC) -->
    <script>
      (function () {
        try {
          var ls = localStorage.getItem('theme');
          var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          if (ls === 'dark' || (!ls && prefersDark)) {
            document.documentElement.classList.add('dark');
          }
        } catch (e) {}
      })();
    </script>

    <!-- Sofortiger Hintergrund passend zur Theme-Entscheidung -->
    <style>
      html { background-color:#f8fafc; }
      html.dark { background-color:#0f172a; }
    </style>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-dvh">
    <!-- Header -->
    <header class="sticky top-0 z-50 relative border-b bg-white/80 dark:bg-ink-900/80 backdrop-blur">
        <div class="container-app flex items-center gap-4 py-3">
            <a href="{{ route('dashboard') }}" class="font-semibold text-ink-800 dark:text-white">MiniCRM</a>

            <nav class="ml-6 hidden md:flex items-center gap-2" role="navigation">
                <x-nav :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav>
                <x-nav :href="route('deals.index')" :active="request()->routeIs('deals.*')">Deals</x-nav>
                <x-nav :href="route('companies.index')" :active="request()->routeIs('companies.*')">Companies</x-nav>
                <x-nav :href="route('contacts.index')" :active="request()->routeIs('contacts.*')">Contacts</x-nav>
            </nav>

            <div class="ml-auto flex items-center gap-2">
                <button type="button"
                        class="px-3 py-2 rounded-lg hover:bg-ink-100 dark:hover:bg-ink-800 focus-ring"
                        x-on:click="const d=document.documentElement; const isDark=d.classList.toggle('dark'); localStorage.theme = isDark ? 'dark':'light'">
                    <span class="sr-only">Theme umschalten</span>
                    🌙
                </button>
                <form method="POST" action="{{ route('logout') }}" class="contents">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-lg border hover:bg-ink-100 dark:hover:bg-ink-800 focus-ring">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Page Content mit Soft-Fade -->
    <main id="page-root" class="container-app py-6 opacity-0 transition-opacity duration-150">
        @if (session('status'))
            <x-toast :message="session('status')" />
        @endif
        {{ $slot }}
    </main>

    <script>
      // Einblenden nach DOM-Ready
      (function () {
        const root = document.getElementById('page-root');
        if (root) requestAnimationFrame(() => root.classList.remove('opacity-0'));
      })();

      // Soft-Fade bei internen Link-Klicks
      document.addEventListener('click', function (e) {
        const a = e.target.closest('a[href]');
        if (!a) return;
        const url = new URL(a.href, location.href);
        const sameOrigin = url.origin === location.origin;
        const isNewTab = e.metaKey || e.ctrlKey || a.target === '_blank';
        if (sameOrigin && !isNewTab && !a.hasAttribute('data-no-fade')) {
          const root = document.getElementById('page-root');
          root?.classList.add('opacity-0');
        }
      }, true);
    </script>
</body>
</html>
