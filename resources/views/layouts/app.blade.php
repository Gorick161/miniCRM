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

    <!-- Set dark mode early to avoid flash of wrong theme -->
    <script>
      (function () {
        try {
          var ls = localStorage.getItem('theme');
          var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          if (ls === 'dark' || (ls === null && prefersDark)) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
        } catch (e) {}
      })();
    </script>

    <!-- Initial background to avoid flash -->
    <style>
      html { background-color: #f8fafc; }      /* light background */
      html.dark { background-color: #040D12; } /* forest-950 */
    </style>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-dvh text-ink-900 dark:text-white bg-white dark:bg-forest-950">
    <!-- Header -->
    <header class="sticky top-0 z-50 relative border-b border-forest-300 dark:border-forest-600 
                   bg-forest-50 dark:bg-forest-800 shadow">
        <div class="container-app flex items-center gap-4 py-3">
            <!-- App Logo / Title -->
            <a href="{{ route('dashboard') }}" class="font-semibold text-forest-900 dark:text-white">
                MiniCRM
            </a>

            <!-- Main Navigation -->
            <nav class="ml-6 hidden md:flex items-center gap-4" role="navigation">
                <x-nav :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav>
                <x-nav :href="route('deals.index')" :active="request()->routeIs('deals.*')">Deals</x-nav>
                <x-nav :href="route('companies.index')" :active="request()->routeIs('companies.*')">Companies</x-nav>
                <x-nav :href="route('contacts.index')" :active="request()->routeIs('contacts.*')">Contacts</x-nav>
            </nav>

            <!-- Theme switch + Logout -->
            <div class="ml-auto flex items-center gap-2">
                <!-- Theme toggle -->
                <button type="button"
                        class="px-3 py-2 rounded-lg hover:bg-forest-600/40 focus-ring transition"
                        x-on:click="const d=document.documentElement; const isDark=d.classList.toggle('dark'); localStorage.theme = isDark ? 'dark':'light'">
                    <span class="sr-only">Toggle theme</span>
                    🌙
                </button>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="contents">
                    @csrf
                    <button type="submit"
                            class="px-3 py-2 rounded-lg border border-forest-300 dark:border-forest-600 
                                   hover:bg-forest-100 dark:hover:bg-forest-700 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Page Content with soft-fade transition -->
    <main id="page-root" class="container-app py-6 opacity-0 transition-opacity duration-150">
        @if (session('status'))
            <x-toast :message="session('status')" />
        @endif
        {{ $slot }}
    </main>

    <script>
      // Fade in after DOM is ready
      (function () {
        const root = document.getElementById('page-root');
        if (root) requestAnimationFrame(() => root.classList.remove('opacity-0'));
      })();

      // Fade out before navigating to internal links
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
