<nav x-data="{ open: false, accountOpen: false }" class="sticky top-0 z-30 border-b border-slate-200 bg-surface/95 backdrop-blur dark:border-slate-800">
    <div class="page-container flex min-h-[72px] items-center justify-between gap-4">
        <a href="{{ Auth::user()->role === 'admin' ? route('admin.index') : route('seller.index') }}" class="inline-flex min-h-11 items-center gap-3 rounded-xl">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-xs font-black text-white dark:text-slate-950">BD</span>
            <span class="font-display text-lg font-bold text-ink">BizDev <span class="hidden text-xs font-semibold text-muted sm:inline">{{ Auth::user()->role === 'admin' ? 'ADMIN' : 'SELLER' }}</span></span>
        </a>

        <div class="hidden items-center gap-1 md:flex">
            @if (Auth::user()->role === 'admin')
                <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">Dashboard</x-nav-link>
                <x-nav-link :href="route('admin.product.index')" :active="request()->routeIs('admin.product.*')">Produk</x-nav-link>
                <x-nav-link :href="route('admin.seller.index')" :active="request()->routeIs('admin.seller.*')">Seller</x-nav-link>
            @else
                <x-nav-link :href="route('seller.index')" :active="request()->routeIs('seller.index')">Dashboard</x-nav-link>
                <x-nav-link :href="route('seller.product.index')" :active="request()->routeIs('seller.product.*')">Produk Saya</x-nav-link>
            @endif
        </div>

        <div class="hidden items-center gap-3 md:flex">
            <x-theme-toggle label="Ganti tema terang atau gelap" />
            <div class="relative" @keydown.escape.window="accountOpen = false">
                <button type="button" @click="accountOpen = !accountOpen" :aria-expanded="accountOpen.toString()" class="inline-flex min-h-11 items-center gap-2 rounded-full border border-slate-200 px-4 text-sm font-bold text-ink hover:border-primary/40 dark:border-slate-700">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-accent/30 text-xs font-black text-ink">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(Auth::user()->name, 0, 1)) }}</span>
                    <span class="max-w-36 truncate">{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="accountOpen" x-cloak x-transition.opacity class="absolute right-0 top-full mt-2 w-56 rounded-2xl border border-slate-200 bg-surface p-2 shadow-soft dark:border-slate-700">
                    <p class="px-3 py-2 text-xs text-muted">{{ Auth::user()->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="block min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">Profil usaha & akun</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="min-h-11 w-full rounded-xl px-3 py-3 text-left text-sm font-semibold text-rose-700 hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-950/40">Keluar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 md:hidden">
            <x-theme-toggle label="Ganti tema terang atau gelap" />
            <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-label="Buka menu dashboard" class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-full border border-slate-200 text-ink dark:border-slate-700">
                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                <svg x-show="open" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak x-transition.opacity class="border-t border-slate-200 bg-surface px-4 py-4 dark:border-slate-800 md:hidden">
        <div class="page-container grid gap-1">
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.index') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">Dashboard Admin</a>
                <a href="{{ route('admin.product.index') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">Produk</a>
                <a href="{{ route('admin.seller.index') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">Seller</a>
            @else
                <a href="{{ route('seller.index') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">Dashboard Seller</a>
                <a href="{{ route('seller.product.index') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">Produk Saya</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">Profil usaha & akun</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="min-h-11 w-full rounded-xl px-3 py-3 text-left text-sm font-semibold text-rose-700 hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-950/40">Keluar</button></form>
        </div>
    </div>
</nav>
