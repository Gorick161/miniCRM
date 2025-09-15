@props([
    'name' => auth()->user()->name ?? 'User',
    'initials' => strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)),
])

<div x-data="{ open: false }" class="relative">
    <!-- Trigger -->
    <button type="button" @click="open = !open" @keydown.escape.window="open=false"
        class="inline-flex items-center gap-2 rounded-xl px-3 py-2 bg-forest-800/50 text-forest-100 hover:bg-forest-700/60 transition">
        <!-- tiny avatar / initials -->
        <span
            class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-forest-600 text-white text-sm font-semibold">
            {{ $initials }}
        </span>
        <span class="hidden sm:block text-sm font-medium">{{ $name }}</span>
        <i data-lucide="chevron-down" class="h-4 w-4 opacity-70"></i>
    </button>

    <!-- Flyout -->
    <div x-show="open" x-transition.origin.top.right @click.outside="open=false"
        class="absolute right-0 mt-2 w-64 rounded-xl border border-forest-700/60 bg-forest-900/95 text-forest-100 shadow-xl backdrop-blur p-2 z-[60]"
        style="display:none">
        <!-- Header -->
        <div class="px-3 py-2 flex items-center gap-3">
            <span
                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-forest-600 text-white font-semibold">
                {{ $initials }}
            </span>
            <div class="min-w-0">
                <div class="truncate text-sm font-semibold">{{ $name }}</div>
                <div class="truncate text-xs text-forest-300">{{ auth()->user()->email ?? '' }}</div>
            </div>
        </div>

        <div class="my-2 h-px bg-forest-700/60"></div>

        <!-- Theme toggle -->
        <button type="button"
            @click="
              const d=document.documentElement;
              const isDark=d.classList.toggle('dark');
              localStorage.theme=isDark?'dark':'light'
            "
            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-forest-700/40 transition">
            <i data-lucide="moon" class="h-5 w-5"></i>
            <span class="text-sm">Toggle dark mode</span>
            <span class="ml-auto text-xs opacity-70"
                x-text="document.documentElement.classList.contains('dark')?'On':'Off'"></span>
        </button>

        <a href="{{ route('dashboard') }}"
            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-forest-700/40 transition">
            <i data-lucide="home" class="h-5 w-5"></i>
            <span class="text-sm">Dashboard</span>
        </a>

        <div class="my-2 h-px bg-forest-700/60"></div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="p-0 m-0">
            @csrf
            <button
                class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-600/20 text-red-200 transition">
                <i data-lucide="log-out" class="h-5 w-5"></i>
                <span class="text-sm">Logout</span>
            </button>
        </form>
    </div>
</div>
