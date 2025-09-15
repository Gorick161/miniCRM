@php
  // Tiny helper for active nav state
  function nav_active($patterns) {
      foreach ((array)$patterns as $p) if (request()->routeIs($p)) return true;
      return false;
  }
@endphp
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'MiniCRM') }}</title>

  <!-- Favicons -->
  <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
  <link rel="alternate icon" type="image/png" href="{{ asset('logo.png') }}">
  <meta name="theme-color" content="#040D12">

  <!-- Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Apply theme & sidebar state BEFORE first paint to prevent FOUC -->
  <script>
    (function () {
      try {
        var doc = document.documentElement;

        // Dark mode
        var lsTheme = localStorage.getItem('theme');
        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (lsTheme === 'dark' || (!lsTheme && prefersDark)) {
          doc.classList.add('dark');
        } else {
          doc.classList.remove('dark');
        }

        // Sidebar collapsed state
        if (localStorage.getItem('sidebar') === 'collapsed') {
          doc.classList.add('sidebar-collapsed');
        }
      } catch(e) {}
    })();
  </script>

  <!-- Minimal background to avoid white flash -->
  <style>
    html{ background:#f8fafc; } html.dark{ background:#040D12; } [x-cloak]{ display:none!important; }

    /* Collapsed sidebar layout rules */
    /* Keep this tiny CSS here so it applies before Tailwind bundles render */
    html.sidebar-collapsed .sidebar-shell{ width:4rem; }
    html.sidebar-collapsed .main-shell{ padding-left:1rem; }         /* small gutter on mobile */
    @media (min-width:1024px){ /* lg and up */
      .main-shell{ padding-left:16rem; }                              /* 64 * 0.25rem = 16rem */
      html.sidebar-collapsed .main-shell{ padding-left:4rem; }        /* collapsed width */
    }

    /* Hide text labels when collapsed; keep icons visible */
    html.sidebar-collapsed .nav-label{ display:none; }
  </style>

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-dvh text-ink-900 dark:text-white bg-white dark:bg-forest-950">

  <!-- ===== Fixed Sidebar ===== -->
  <aside
    class="fixed inset-y-0 left-0 z-40
           w-64
           border-r border-forest-200/60 dark:border-forest-800
           bg-forest-50/80 dark:bg-forest-900/80 backdrop-blur
           transition-[width] duration-300 will-change-[width]
           sidebar-shell">

    <!-- Brand -->
    <div class="h-16 px-4 flex items-center gap-3 border-b border-forest-200/60 dark:border-forest-800">
      <img src="{{ asset('logo.svg') }}" alt="MiniCRM Logo" class="h-8 w-8 shrink-0">
      <a href="{{ route('dashboard') }}" class="font-semibold text-forest-900 dark:text-white truncate nav-label">MiniCRM</a>
    </div>

    <!-- Main Nav -->
    @php
    $items = [
        ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => route('dashboard'), 'active' => nav_active('dashboard')],
        ['label' => 'Deals',     'icon' => 'handshake',        'route' => route('deals.index'), 'active' => nav_active('deals.*')],
        ['label' => 'Companies', 'icon' => 'building-2',       'route' => route('companies.index'), 'active' => nav_active('companies.*')],
        ['label' => 'Contacts',  'icon' => 'users',            'route' => route('contacts.index'), 'active' => nav_active('contacts.*')],
    ];
@endphp

<nav class="p-3 space-y-1">
  @foreach ($items as $it)
    <a href="{{ $it['route'] }}"
       class="group flex items-center gap-3 px-3 py-2 rounded-lg transition
              {{ $it['active']
                   ? 'bg-forest-500/15 text-white border border-forest-500/30'
                   : 'text-forest-700 dark:text-forest-300 hover:bg-forest-500/10' }}">
      <x-dynamic-component :component="'lucide-' . $it['icon']"
                           class="h-5 w-5 opacity-80 group-hover:opacity-100"/>
      <span class="text-sm font-medium truncate">{{ $it['label'] }}</span>
    </a>
  @endforeach
</nav>

    <!-- Sidebar Footer (minimal; profile controls live in top-right menu) -->
    <div class="mt-auto p-3 border-t border-forest-200/60 dark:border-forest-800">
      <div class="nav-label text-xs text-forest-400 text-center opacity-70">MiniCRM</div>
    </div>
  </aside>

  <!-- ===== Topbar + Main content (pushed by sidebar) ===== -->
  <div class="min-h-dvh main-shell transition-[padding] duration-300 will-change-[padding]">
    <header class="h-16 border-b border-forest-200/60 dark:border-forest-800
                   bg-white/70 dark:bg-forest-900/70 backdrop-blur sticky top-0 z-30">
      <div class="h-full px-4 lg:px-8 flex items-center gap-3">

        <!-- Collapse toggle (mobile + desktop) -->
        <button class="p-2 rounded-lg hover:bg-forest-700/30"
                onclick="
                  (function(){
                    const d=document.documentElement;
                    const collapsed=d.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebar', collapsed ? 'collapsed' : 'expanded');
                  })()"
                aria-label="Toggle sidebar">☰</button>

        <!-- Search -->
        <form action="#" class="hidden md:flex items-center gap-2 flex-1 max-w-xl">
          <input type="search" placeholder="Search…" class="ui-input w-full">
        </form>

        <!-- Right actions: profile menu -->
        <div class="ml-auto flex items-center gap-2">
          <x-user-menu />
        </div>
      </div>
    </header>

    <!-- Page content with soft fade -->
    <main id="page-root" class="px-4 lg:px-8 py-6 opacity-0 transition-opacity duration-150">
      @yield('content')
    </main>
  </div>

  <!-- Page fade + theme wiring -->
  <script>
    // Fade in content after paint
    (function () {
      const root = document.getElementById('page-root');
      if (root) requestAnimationFrame(() => root.classList.remove('opacity-0'));
    })();

    // Fade-out on internal link navigation (keeps sidebar/header stable)
    document.addEventListener('click', function (e) {
      const a = e.target.closest('a[href]');
      if (!a) return;
      const url = new URL(a.href, location.href);
      const sameOrigin = url.origin === location.origin;
      const newTab = e.metaKey || e.ctrlKey || a.target === '_blank';
      if (sameOrigin && !newTab && !a.hasAttribute('data-no-fade')) {
        const root = document.getElementById('page-root');
        root && root.classList.add('opacity-0');
      }
    }, true);

  
    document.addEventListener('click', function (e) {
      const btn = e.target.closest('.js-theme');
      if (!btn) return;
      const d = document.documentElement;
      const nowDark = d.classList.toggle('dark');
      localStorage.setItem('theme', nowDark ? 'dark' : 'light');
    });
  </script>
</body>
</html>
