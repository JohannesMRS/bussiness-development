<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Seller
        </h2>
    </x-slot>
        <x-slot name="header">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Ruang kerja seller</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">Halo, {{ auth()->user()->name }}</h1></div>
                <x-button :href="route('seller.product.create')">+ Ajukan produk</x-button>
            </div>
        </x-slot>
        <main class="page-container space-y-8 py-8 sm:py-10">
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                @foreach ([['label' => 'Menunggu tinjauan', 'status' => 'pending', 'tone' => 'bg-amber-400'], ['label' => 'Disetujui', 'status' => 'approved', 'tone' => 'bg-emerald-500'], ['label' => 'Perlu diperbaiki', 'status' => 'rejected', 'tone' => 'bg-rose-500']] as $stat)
                    <article class="dashboard-card flex items-center justify-between gap-4">
                        <div><p class="text-sm font-semibold text-muted">{{ $stat['label'] }}</p><p class="mt-2 font-display text-3xl font-bold text-ink">{{ $productCounts[$stat['status']] ?? 0 }}</p></div>
                        <span class="h-12 w-12 rounded-2xl {{ $stat['tone'] }} opacity-20" aria-hidden="true"></span>
                    </article>
                @endforeach
            </section>

            <section class="dashboard-card overflow-hidden p-0">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 px-5 py-5 dark:border-slate-700 sm:px-6">
                    <div><h2 class="font-display text-xl font-bold text-ink">Produk terbaru</h2><p class="mt-1 text-sm text-muted">Status pengajuan dan informasi produk Anda</p></div>
                    <x-button :href="route('seller.product.index')" variant="secondary">Semua produk</x-button>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($products as $product)
                        <a href="{{ route('seller.product.show', $product) }}" class="flex min-h-16 items-center justify-between gap-4 px-5 py-4 transition hover:bg-primary/5 sm:px-6">
                            <div class="min-w-0"><p class="truncate font-semibold text-ink">{{ $product->title }}</p><p class="mt-1 truncate text-xs text-muted">{{ $product->category->name }} · Rp {{ number_format((int) $product->price, 0, ',', '.') }}</p>
                                @if ($product->rejection_reason)<p class="mt-1 text-xs font-medium text-rose-700 dark:text-rose-300">{{ $product->rejection_reason }}</p>@endif
                            </div>
                            <x-badge :variant="match($product->status) {'approved' => 'success', 'pending' => 'warning', default => 'danger'}">{{ match($product->status) {'approved' => 'Disetujui', 'pending' => 'Menunggu tinjauan', default => 'Ditolak'} }}</x-badge>
                        </a>
                    @empty
                        <div class="p-6"><x-empty-state title="Belum ada produk" description="Ajukan produk pertama Anda untuk mulai tampil di katalog." :action="'Ajukan produk'" :href="route('seller.product.create')" /></div>
                    @endforelse
                </div>
                <div class="px-5 py-4 sm:px-6">{{ $products->links() }}</div>
            </section>
    </main>
</x-app-layout>