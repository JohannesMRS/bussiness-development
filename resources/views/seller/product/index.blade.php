<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Ruang kerja seller</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">Produk saya</h1></div>
            <x-button :href="route('seller.product.create')">+ Ajukan produk</x-button>
        </div>
    </x-slot>
    <main class="page-container space-y-5 py-8 sm:py-10">
        @if (session('success'))<p role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">{{ session('success') }}</p>@endif
        @forelse ($products as $product)
            <article class="dashboard-card">
                <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2"><x-badge>{{ $product->category->name }}</x-badge><x-badge :variant="match($product->status) {'approved' => 'success', 'pending' => 'warning', default => 'danger'}">{{ match($product->status) {'approved' => 'Disetujui', 'pending' => 'Menunggu tinjauan', default => 'Ditolak'} }}</x-badge></div>
                        <h2 class="mt-3 font-display text-xl font-bold text-ink"><a href="{{ route('seller.product.show', $product) }}" class="hover:text-primary">{{ $product->title }}</a></h2>
                        <p class="mt-1 text-sm text-muted">Rp {{ number_format((int) $product->price, 0, ',', '.') }} · Fee Rp {{ number_format((int) $product->fee_amount, 0, ',', '.') }}</p>
                        @if ($product->rejection_reason)<p class="mt-3 rounded-xl bg-rose-50 px-4 py-3 text-sm leading-6 text-rose-800 dark:bg-rose-950/40 dark:text-rose-200"><strong>Catatan admin:</strong> {{ $product->rejection_reason }}</p>@endif
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <x-button :href="route('seller.product.show', $product)" variant="secondary">Detail</x-button>
                        <x-button :href="route('seller.product.edit', $product)" variant="secondary">Edit</x-button>
                        @if ($product->payment_proof)<x-button :href="route('seller.product.payment-proof', $product)" variant="quiet">Bukti</x-button>@endif
                        <form method="POST" action="{{ route('seller.product.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')<x-button type="submit" variant="danger">Hapus</x-button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <x-empty-state title="Belum ada produk" description="Mulai dengan mengajukan produk atau jasa pertama Anda." :action="'Ajukan produk'" :href="route('seller.product.create')" />
        @endforelse
        {{ $products->links() }}
    </main>
</x-app-layout>
