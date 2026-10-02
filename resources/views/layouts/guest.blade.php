<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'BizDev HMPS MI Polmed'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-page font-sans text-ink antialiased">
        <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-hero-glow px-4 py-10 sm:px-6">
            <div class="absolute right-5 top-5 sm:right-8 sm:top-8"><x-theme-toggle label="Ganti tema terang atau gelap" /></div>
            <div class="relative w-full max-w-md">
                <a href="{{ route('home') }}" class="mb-8 flex flex-col items-center gap-3 text-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-3xl bg-primary text-xl font-black text-white shadow-soft dark:text-slate-950">BD</span>
                    <span class="font-display text-2xl font-bold text-ink">BizDev HMPS MI Polmed</span>
                </a>
                <div class="rounded-[2rem] border border-slate-200 bg-surface p-6 shadow-soft sm:p-9 dark:border-slate-700">
                    {{ $slot }}
                </div>
                <p class="mt-6 text-center text-xs leading-5 text-muted">Katalog ini tidak memproses transaksi. Pemesanan dilakukan langsung melalui WhatsApp penjual.</p>
            </div>
        </div>
    </body>
</html>
