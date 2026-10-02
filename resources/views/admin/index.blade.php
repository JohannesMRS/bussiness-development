<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Ruang kerja admin</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">Dashboard</h1></div>
            <p class="text-sm text-muted">Ringkasan katalog dan pengajuan seller</p>
        </div>
    </x-slot>
    <main class="page-container space-y-8 py-8 sm:py-10">
        <section class="grid grid-cols-2 gap-4 lg:grid-cols-5">
            @foreach ([['label' => 'Total seller', 'value' => $sellerCount, 'tone' => 'primary'], ['label' => 'Seller aktif', 'value' => $activeSellerCount, 'tone' => 'accent'], ['label' => 'Menunggu tinjauan', 'value' => $productCounts['pending'] ?? 0, 'tone' => 'warning'], ['label' => 'Disetujui', 'value' => $productCounts['approved'] ?? 0, 'tone' => 'success'], ['label' => 'Ditolak', 'value' => $productCounts['rejected'] ?? 0, 'tone' => 'danger']] as $stat)
                <article class="dashboard-card {{ $loop->last ? 'col-span-2 lg:col-span-1' : '' }}">
                    <div class="flex items-center justify-between gap-2"><p class="text-xs font-semibold text-muted sm:text-sm">{{ $stat['label'] }}</p><span class="h-2.5 w-2.5 rounded-full {{ $stat['tone'] === 'accent' ? 'bg-accent' : ($stat['tone'] === 'danger' ? 'bg-rose-500' : ($stat['tone'] === 'success' ? 'bg-emerald-500' : 'bg-primary')) }}"></span></div>
                    <p class="mt-4 font-display text-3xl font-bold text-ink sm:text-4xl">{{ $stat['value'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.4fr_.6fr]">
            <div class="dashboard-card overflow-hidden p-0">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 px-5 py-5 dark:border-slate-700 sm:px-6">
                    <div><h2 class="font-display text-xl font-bold text-ink">Produk terbaru</h2><p class="mt-1 text-sm text-muted">Pengajuan dan katalog terkini</p></div>
                    <x-button :href="route('admin.product.index')" variant="secondary">Kelola produk</x-button>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($recentProducts as $product)
                        <a href="{{ route('admin.product.show', $product) }}" class="flex min-h-16 items-center justify-between gap-4 px-5 py-4 transition hover:bg-primary/5 sm:px-6">
                            <div class="min-w-0"><p class="truncate font-semibold text-ink">{{ $product->title }}</p><p class="mt-1 truncate text-xs text-muted">{{ $product->seller->bussiness_name ?: $product->seller->name }}</p></div>
                            <x-badge :variant="match($product->status) {'approved' => 'success', 'pending' => 'warning', default => 'danger'}">{{ match($product->status) {'approved' => 'Disetujui', 'pending' => 'Menunggu tinjauan', default => 'Ditolak'} }}</x-badge>
                        </a>
                    @empty
                        <p class="p-6 text-sm text-muted">Belum ada produk.</p>
                    @endforelse
                </div>
            </div>

            <aside class="rounded-3xl bg-primary p-6 text-white shadow-soft dark:text-slate-950">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 text-xl font-black dark:bg-white/40">+</span>
                <h2 class="mt-5 font-display text-2xl font-bold">Akses cepat</h2>
                <p class="mt-2 text-sm leading-6 text-white/75 dark:text-slate-900/75">Kelola listing dan akun seller dari satu tempat.</p>
                <div class="mt-6 grid gap-3">
                    <x-button :href="route('admin.product.create')" variant="accent">Tambah produk admin</x-button>
                    <x-button :href="route('admin.seller.create')" class="bg-white text-primary hover:bg-slate-100 dark:bg-slate-950 dark:text-white">Buat akun seller</x-button>
                </div>
            </aside>
        </section>
    </main>
</x-app-layout>