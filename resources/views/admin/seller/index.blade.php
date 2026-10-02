<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Panel admin</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">Manajemen seller</h1></div>
            <x-button :href="route('admin.seller.create')">+ Buat akun seller</x-button>
        </div>
    </x-slot>
    <main class="page-container space-y-5 py-8 sm:py-10">
        @if (session('success'))<p role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">{{ session('success') }}</p>@endif
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($sellers as $seller)
                <article class="dashboard-card flex flex-col">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0"><p class="text-xs font-bold uppercase tracking-wider text-muted">{{ $seller->name }}</p><h2 class="mt-1 truncate font-display text-xl font-bold text-ink">{{ $seller->bussiness_name ?: $seller->name }}</h2></div>
                        <x-badge :variant="$seller->is_active ? 'success' : 'danger'">{{ $seller->is_active ? 'Aktif' : 'Nonaktif' }}</x-badge>
                    </div>
                    <p class="mt-3 truncate text-sm text-muted">{{ $seller->email }}</p>
                    <p class="mt-1 text-sm text-muted">{{ $seller->major }}</p>
                    <div class="mt-5 flex gap-2 border-t border-slate-200 pt-4 dark:border-slate-700">
                        <x-button :href="route('admin.seller.edit', $seller)" variant="secondary" class="flex-1">Edit & reset password</x-button>
                        <form method="POST" action="{{ route('admin.seller.active', $seller) }}">
                            @csrf @method('PATCH')
                            <x-button type="submit" :variant="$seller->is_active ? 'danger' : 'primary'">{{ $seller->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</x-button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="md:col-span-2 xl:col-span-3"><x-empty-state title="Belum ada akun seller" description="Buat akun untuk memberi mahasiswa akses mengelola produk." :action="'Buat akun seller'" :href="route('admin.seller.create')" /></div>
            @endforelse
        </section>
        {{ $sellers->links() }}
    </main>
</x-app-layout>