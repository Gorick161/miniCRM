@php
  // tiny helper to mark active nav items
  function nav_active($patterns){ foreach((array)$patterns as $p) if(request()->routeIs($p)) return true; return false; }
@endphp
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'MiniCRM') }}</title>

  <!-- Inter font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Set theme early to avoid FOUC -->
  <script>
    (function () {
      try {
        const ls = localStorage.getItem('theme');
        const prefersDark = matchMedia('(prefers-color-scheme: dark)').matches;
        if (ls === 'dark' || (ls === null && prefersDark)) document.documentElement.classList.add('dark');
      } catch {}
    })();
  </script>

  <!-- Neutral initial background (light+dark) -->
  <style>
    html { background:#f8fafc; }
    html.dark { background:#040D12; } /* forest-950 */
  </style>

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-dvh bg-white text-ink-900 dark:bg-forest-950 dark:text-white">

<div x-data="{ collapsed:false }" class="min-h-dvh">

  <!-- ========= SIDEBAR (fixed) ========= -->
  <aside
    class="fixed inset-y-0 left-0 z-40 border-r border-forest-200/60 dark:border-forest-800
           bg-forest-50/80 dark:bg-forest-900/80 backdrop-blur
           hidden lg:flex flex-col transition-[width] duration-200 ease-out"
    :class="collapsed ? 'w-16' : 'w-64'">

    <!-- Brand / collapse toggle -->
    <div class="h-16 flex items-center justify-between gap-2 px-3">
      <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        <span class="inline-flex h-8 w-8 rounded-lg bg-forest-500/20 items-center justify-center">💠</span>
        <span class="font-semibold text-forest-900 dark:text-white" x-show="!collapsed">MiniCRM</span>
      </a>
      <button
        type="button"
        class="p-2 rounded-lg hover:bg-forest-600/30 transition"
        @click="collapsed=!collapsed"
        :aria-label="collapsed ? 'Expand sidebar' : 'Collapse sidebar'">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path :d="collapsed ? 'M15 18l-6-6 6-6' : 'M9 6l6 6-6 6'"></path>
        </svg>
      </button>
    </div>

    <!-- NAV -->
    <nav class="flex-1 p-2 space-y-1">
      @php
        $items = [
          ['label'=>'Dashboard','icon'=>'M3 12h18','route'=>route('dashboard'),'active'=>nav_active('dashboard')],
          ['label'=>'Deals','icon'=>'M3 7h18M3 12h18M3 17h12','route'=>route('deals.index'),'active'=>nav_active('deals.*')],
          ['label'=>'Companies','icon'=>'M4 6h16v12H4z','route'=>route('companies.index'),'active'=>nav_active('companies.*')],
          ['label'=>'Contacts','icon'=>'M12 12a5 5 0 100-10 5 5 0 000 10z M4 20a8 8 0 1116 0','route'=>route('contacts.index'),'active'=>nav_active('contacts.*')],
        ];
      @endphp

      @foreach ($items as $it)
        <a href="{{ $it['route'] }}"
           class="group flex items-center gap-3 px-3 py-2 rounded-lg transition
                  {{ $it['active']
                      ? 'bg-forest-500/15 text-white border border-forest-500/30'
                      : 'text-forest-700 dark:text-forest-300 hover:bg-forest-500/10' }}"
           title="{{ $it['label'] }}">
          <svg class="h-5 w-5 opacity-80 group-hover:opacity-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="{{ $it['icon'] }}"></path>
          </svg>
          <span class="text-sm font-medium" x-show="!collapsed" x-transition.opacity>{{ $it['label'] }}</span>
        </a>
      @endforeach
    </nav>

    <!-- Footer actions -->
    <div class="p-2 mt-auto border-t border-forest-200 dark:border-forest-800">
      <div class="flex items-center justify-between">
        <!-- Theme toggle (HIDDEN when collapsed) -->
        <button
          type="button"
          class="px-3 py-2 rounded-lg hover:bg-forest-600/30 transition"
          x-show="!collapsed"
          @click="const d=document.documentElement; const on=d.classList.toggle('dark'); localStorage.theme=on?'dark':'light'">
          <span class="sr-only">Toggle theme</span> 🌙
        </button>

        <!-- Logout (always visible; text only when expanded) -->
        <form method="POST" action="{{ route('logout') }}" class="ml-auto">
          @csrf
          <button type="submit"
                  class="flex items-center gap-2 px-3 py-2 rounded-lg border border-forest-300 dark:border-forest-700 hover:bg-forest-700/40">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
            <span x-show="!collapsed" x-transition.opacity>Logout</span>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <!-- ========= TOPBAR (mobile + desktop) ========= -->
  <header class="fixed top-0 right-0 left-0 h-16 z-30 border-b border-forest-200/60 dark:border-forest-800
                  bg-forest-50/60 dark:bg-forest-900/60 backdrop-blur lg:left-auto"
          :style="collapsed ? 'left:4rem' : 'left:16rem'">
    <div class="h-full px-4 lg:px-8 flex items-center gap-3">
      <!-- Mobile burger -->
      <div class="lg:hidden">
        <button class="p-2 rounded-lg hover:bg-forest-700/30" x-data @click="$dispatch('toggle-drawer')" aria-label="Open menu">☰</button>
      </div>

      <!-- Search -->
      <form action="#" class="hidden md:flex items-center gap-2 flex-1 max-w-xl">
        <input type="search" placeholder="Search…" class="ui-input w-full" />
      </form>

      <!-- Right CTAs -->
      <div class="ml-auto flex items-center gap-2">
        <a href="{{ route('deals.index') }}" class="btn-outline hidden sm:inline-flex">New Deal</a>
        <a href="{{ route('companies.index') }}" class="btn hidden sm:inline-flex">New Company</a>
      </div>
    </div>
  </header>

  <!-- ========= PAGE CONTENT ========= -->
  <main id="page-root"
        class="pt-20 px-4 lg:px-8 opacity-0 transition-opacity duration-150"
        :style="collapsed ? 'padding-left:4rem' : 'padding-left:16rem'">
    @yield('content')
  </main>

</div>

<script>
  // soft fade in
  (function () {
    const root = document.getElementById('page-root');
    if (root) requestAnimationFrame(() => root.classList.remove('opacity-0'));
  })();

  // soft fade out before SPA-like navigation
  document.addEventListener('click', function (e) {
    const a = e.target.closest('a[href]');
    if (!a) return;
    const url = new URL(a.href, location.href);
    const same = url.origin === location.origin;
    const newTab = e.metaKey || e.ctrlKey || a.target === '_blank';
    if (same && !newTab && !a.hasAttribute('data-no-fade')) {
      document.getElementById('page-root')?.classList.add('opacity-0');
    }
  }, true);
</script>
</body>
</html>
