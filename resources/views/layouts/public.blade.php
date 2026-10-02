<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('public.brand.name'))</title>
    <meta name="description" content="@yield('meta_description', __('public.brand.description'))">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ __('public.brand.name') }}">
    <meta property="og:title" content="@yield('title', __('public.brand.name'))">
    <meta property="og:description" content="@yield('meta_description', __('public.brand.description'))">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <script>
        (() => {
            try {
                const savedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', savedTheme ? savedTheme === 'dark' : prefersDark);
            } catch {
                document.documentElement.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-page font-sans text-ink antialiased selection:bg-secondary selection:text-white">
    <div class="min-h-screen overflow-hidden">
        <header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200/80 bg-surface/90 backdrop-blur-xl dark:border-slate-800/80">
            <div class="page-container flex min-h-[76px] items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="group inline-flex min-h-11 items-center gap-3 rounded-xl" aria-label="{{ __('public.brand.name') }}">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary text-sm font-black tracking-tight text-white shadow-soft dark:text-slate-950">BD</span>
                    <span class="leading-tight">
                        <span class="block font-display text-lg font-bold text-ink">Bussiness Development</span>
                        <span class="hidden text-[11px] font-semibold tracking-wide text-muted sm:block">HMPS Manajemen Informatika</span>
                    </span>
                </a>

                <nav aria-label="{{ __('public.nav.home') }}" class="hidden items-center gap-1 lg:flex">
                    <a href="{{ route('home') }}" @class(['rounded-full px-4 py-2.5 text-sm font-semibold transition hover:bg-primary/5 hover:text-primary', 'bg-primary/5 text-primary' => request()->routeIs('home'), 'text-muted' => ! request()->routeIs('home')])>{{ __('public.nav.home') }}</a>
                    <a href="{{ route('catalog.index') }}" @class(['rounded-full px-4 py-2.5 text-sm font-semibold transition hover:bg-primary/5 hover:text-primary', 'bg-primary/5 text-primary' => request()->routeIs('catalog.*') || request()->routeIs('products.*'), 'text-muted' => ! request()->routeIs('catalog.*') && ! request()->routeIs('products.*')])>{{ __('public.nav.catalog') }}</a>
                    <a href="{{ route('sellers.index') }}" @class(['rounded-full px-4 py-2.5 text-sm font-semibold transition hover:bg-primary/5 hover:text-primary', 'bg-primary/5 text-primary' => request()->routeIs('sellers.*'), 'text-muted' => ! request()->routeIs('sellers.*')])>{{ __('public.nav.sellers') }}</a>
                    <a href="{{ route('about') }}" @class(['rounded-full px-4 py-2.5 text-sm font-semibold transition hover:bg-primary/5 hover:text-primary', 'bg-primary/5 text-primary' => request()->routeIs('about'), 'text-muted' => ! request()->routeIs('about')])>{{ __('public.nav.about') }}</a>
                </nav>

                <div class="hidden items-center gap-2 lg:flex">
                    <form method="POST" action="{{ route('locale.switch') }}">
                        @csrf
                        <input type="hidden" name="locale" value="{{ app()->getLocale() === 'id' ? 'en' : 'id' }}">
                        <button type="submit" aria-label="{{ __('public.language.switch') }}" class="min-h-11 rounded-full px-3 text-xs font-extrabold tracking-wide text-muted transition hover:bg-primary/5 hover:text-primary">{{ app()->getLocale() === 'id' ? 'ID → EN' : 'EN → ID' }}</button>
                    </form>
                    <x-theme-toggle />
                    <a href="{{ route('login') }}" class="min-h-11 rounded-full border border-primary/20 px-4 py-2.5 text-sm font-bold text-primary transition hover:bg-primary hover:text-white dark:text-blue-200 dark:hover:text-slate-950">{{ __('public.nav.login') }}</a>
                </div>

                <div class="flex items-center gap-2 lg:hidden">
                    <x-theme-toggle />
                    <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-label="{{ __('public.nav.open_menu') }}" class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-full border border-slate-200 text-ink dark:border-slate-700">
                        <svg x-show="!open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <svg x-show="open" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </div>

            <div x-show="open" x-cloak x-transition.opacity class="border-t border-slate-200 bg-surface px-4 pb-5 pt-3 dark:border-slate-800 lg:hidden">
                <nav class="page-container grid gap-1" aria-label="{{ __('public.nav.open_menu') }}">
                    <a @click="open = false" href="{{ route('home') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">{{ __('public.nav.home') }}</a>
                    <a @click="open = false" href="{{ route('catalog.index') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">{{ __('public.nav.catalog') }}</a>
                    <a @click="open = false" href="{{ route('sellers.index') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">{{ __('public.nav.sellers') }}</a>
                    <a @click="open = false" href="{{ route('about') }}" class="min-h-11 rounded-xl px-3 py-3 text-sm font-semibold text-ink hover:bg-primary/5">{{ __('public.nav.about') }}</a>
                    <div class="mt-2 flex items-center justify-between border-t border-slate-200 pt-3 dark:border-slate-800">
                        <form method="POST" action="{{ route('locale.switch') }}">
                            @csrf
                            <input type="hidden" name="locale" value="{{ app()->getLocale() === 'id' ? 'en' : 'id' }}">
                            <button type="submit" class="min-h-11 rounded-full px-3 text-sm font-bold text-primary dark:text-blue-200">{{ app()->getLocale() === 'id' ? 'Ganti ke English' : 'Switch to Bahasa Indonesia' }}</button>
                        </form>
                        <a href="{{ route('login') }}" class="min-h-11 rounded-full bg-primary px-4 py-3 text-sm font-bold text-white dark:text-slate-950">{{ __('public.nav.login') }}</a>
                    </div>
                </nav>
            </div>
        </header>

        <main id="main-content">
            @yield('content')
        </main>

        <footer class="mt-20 border-t border-slate-200 bg-surface dark:border-slate-800">
            <div class="page-container grid gap-10 py-12 md:grid-cols-[1.4fr_1fr_1fr] md:py-16">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-xs font-black text-white dark:text-slate-950">BD</span>
                        <span class="font-display text-lg font-bold text-ink">{{ __('public.brand.name') }}</span>
                    </a>
                    <p class="mt-4 max-w-sm text-sm leading-6 text-muted">{{ __('public.footer.tagline') }}</p>
                    <p class="mt-3 max-w-sm text-xs leading-5 text-muted">{{ __('public.footer.direct_order') }}</p>
                </div>
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-ink">{{ __('public.nav.catalog') }}</h2>
                    <ul class="mt-4 grid gap-3 text-sm text-muted">
                        <li><a class="hover:text-primary" href="{{ route('catalog.index') }}">{{ __('public.nav.catalog') }}</a></li>
                        <li><a class="hover:text-primary" href="{{ route('sellers.index') }}">{{ __('public.nav.sellers') }}</a></li>
                        <li><a class="hover:text-primary" href="{{ route('about') }}">{{ __('public.nav.about') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-ink">HMPS MI Polmed</h2>
                    <p class="mt-4 text-sm leading-6 text-muted">{{ __('public.footer.copyright') }}<br>Politeknik Negeri Medan</p>
                    <a class="mt-4 inline-flex min-h-11 items-center text-sm font-bold text-primary hover:text-secondary dark:text-blue-200" href="{{ route('login') }}">{{ __('public.nav.login') }} <span class="ms-2" aria-hidden="true">→</span></a>
                </div>
            </div>
            <div class="border-t border-slate-200 py-4 text-center text-xs text-muted dark:border-slate-800">© {{ date('Y') }} {{ __('public.footer.copyright') }}</div>
        </footer>
    </div>
</body>
</html>
