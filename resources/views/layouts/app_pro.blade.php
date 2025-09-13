@php
  // Helper: active state check for current route groups
  function nav_active($patterns) {
      foreach ((array)$patterns as $p) if (request()->routeIs($p)) return true;
      return false;
  }
@endphp
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'MiniCRM') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Set dark mode early to avoid FOUC -->
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

  <!-- Prevent white flash on first paint -->
  <style>
    html { background-color: #f8fafc; }
    html.dark { background-color: #040D12; } /* forest-950 */
  </style>

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-dvh bg-white text-ink-900 dark:bg-forest-950 dark:text-white">
<div x-data="{ open:false }" class="min-h-dvh flex">

  <!-- ====== Sidebar ====== -->
  <aside class="hidden lg:flex lg:w-64 shrink-0 flex-col border-r border-forest-200 dark:border-forest-800
                 bg-forest-50 dark:bg-forest-900/80 backdrop-blur">
    <!-- Brand -->
    <div class="h-16 flex items-center gap-2 px-5 border-b border-forest-200 dark:border-forest-800">
      <span class="inline-flex h-8 w-8 rounded-lg bg-forest-500/20 items-center justify-center">💠</span>
      <a href="{{ route('dashboard') }}" class="font-semibold text-forest-900 dark:text-white">MiniCRM</a>
    </div>

    <!-- Nav -->
    <nav class="flex-1 p-3 space-y-1">
      @php
        $items = [
          ['label'=>'Dashboard','icon'=>'M3 12h18','route'=>route('dashboard'),'active'=>nav_active('dashboard')],
          ['label'=>'Deals','icon'=>'M3 7h18M3 12h18M3 17h12','route'=>route('deals.index'),'active'=>nav_active('deals.*')],
          ['label'=>'Companies','icon'=>'M4 6h16v12H4z','route'=>route('companies.index'),'active'=>nav_active('companies.*')],
          ['label'=>'Contacts','icon'=>'M12 12a5 5 0 100-10 5 5 0 000 10z M4 20a8 8 0 1116 0','route'=>route('contacts.index'),'active'=>nav_active('contacts.*')],
          // Add more entries when you add sections: Reports, Products, Settings, ...
        ];
      @endphp

      @foreach ($items as $it)
        <a href="{{ $it['route'] }}"
           class="group flex items-center gap-3 px-3 py-2 rounded-lg transition
                  {{ $it['active']
                     ? 'bg-forest-500/15 text-white border border-forest-500/30'
                     : 'text-forest-700 dark:text-forest-300 hover:bg-forest-500/10' }}">
          <!-- tiny inline SVG to avoid extra deps -->
          <svg class="h-5 w-5 opacity-80 group-hover:opacity-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="{{ $it['icon'] }}"></path>
          </svg>
          <span class="text-sm font-medium">{{ $it['label'] }}</span>
        </a>
      @endforeach
    </nav>

    <!-- Footer actions -->
    <div class="p-3 mt-auto border-t border-forest-200 dark:border-forest-800">
      <div class="flex items-center justify-between">
        <!-- Theme toggle -->
        <button type="button"
                class="px-3 py-2 rounded-lg hover:bg-forest-600/30 transition"
                x-on:click="const d=document.documentElement; const isDark=d.classList.toggle('dark'); localStorage.theme = isDark ? 'dark':'light'">
          <span class="sr-only">Toggle theme</span> 🌙
        </button>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="px-3 py-2 rounded-lg border border-forest-300 dark:border-forest-700 hover:bg-forest-700/40">
            Logout
          </button>
        </form>
      </div>
    </div>
  </aside>

  <!-- ====== Mobile topbar (also visible on desktop as header) ====== -->
  <div class="flex-1 min-w-0 flex flex-col">
    <header class="h-16 border-b border-forest-200 dark:border-forest-800 bg-forest-50/60 dark:bg-forest-900/60 backdrop-blur">
      <div class="h-full px-4 lg:px-8 flex items-center gap-3">
        <!-- Mobile burger -->
        <button class="lg:hidden p-2 rounded-lg hover:bg-forest-700/30" @click="open = !open" aria-label="Open sidebar">☰</button>

        <!-- Search -->
        <form action="#" class="hidden md:flex items-center gap-2 flex-1 max-w-xl">
          <!-- Using our global white input style -->
          <input type="search" placeholder="Search…" class="ui-input w-full" />
        </form>

        <!-- Right actions -->
        <div class="ml-auto flex items-center gap-2">
          <a href="{{ route('deals.index') }}" class="btn-outline hidden sm:inline-flex">New Deal</a>
          <a href="{{ route('companies.index') }}" class="btn hidden sm:inline-flex">New Company</a>
        </div>
      </div>
    </header>

        <!-- ====== Page content ====== -->
    <main id="page-root" class="opacity-0 transition-opacity duration-150 px-4 lg:px-8 py-6">
      {{-- Prefer section("content") if present (views using @extends), else fall back to component $slot (x-app-layout) --}}
      @if (trim($__env->yieldContent('content')))
        @yield('content')
      @else
        {{ $slot ?? '' }}
      @endif
    </main>

  </div>

  <!-- Mobile drawer (simple version) -->
  <div class="lg:hidden fixed inset-0 z-50" x-show="open" x-transition.opacity @click.self="open=false">
    <div class="absolute inset-y-0 left-0 w-72 bg-forest-900 border-r border-forest-800 p-4">
      <div class="flex items-center justify-between mb-4">
        <span class="font-semibold">MiniCRM</span>
        <button class="p-2" @click="open=false">✕</button>
      </div>
      <nav class="space-y-1">
        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-forest-700/50 {{ nav_active('dashboard') ? 'bg-forest-700/50' : '' }}">Dashboard</a>
        <a href="{{ route('deals.index') }}" class="block px-3 py-2 rounded-lg hover:bg-forest-700/50 {{ nav_active('deals.*') ? 'bg-forest-700/50' : '' }}">Deals</a>
        <a href="{{ route('companies.index') }}" class="block px-3 py-2 rounded-lg hover:bg-forest-700/50 {{ nav_active('companies.*') ? 'bg-forest-700/50' : '' }}">Companies</a>
        <a href="{{ route('contacts.index') }}" class="block px-3 py-2 rounded-lg hover:bg-forest-700/50 {{ nav_active('contacts.*') ? 'bg-forest-700/50' : '' }}">Contacts</a>
      </nav>
    </div>
  </div>
</div>

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
