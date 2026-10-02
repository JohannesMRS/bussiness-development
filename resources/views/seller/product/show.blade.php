<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Produk saya</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">{{ $product->title }}</h1></div>
            <x-button :href="route('seller.product.edit', $product)">Edit produk</x-button>
        </div>
    </x-slot>
    <main class="page-container grid gap-6 py-8 sm:py-10 lg:grid-cols-[1fr_.7fr]">
        <article class="dashboard-card space-y-5">
            <div class="flex flex-wrap gap-2"><x-badge>{{ $product->category->name }}</x-badge><x-badge :variant="match($product->status) {'approved' => 'success', 'pending' => 'warning', default => 'danger'}">{{ match($product->status) {'approved' => 'Disetujui', 'pending' => 'Menunggu tinjauan', default => 'Ditolak'} }}</x-badge></div>
            @if ($product->rejection_reason)<p class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm leading-6 text-rose-800 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-200"><strong>Catatan admin:</strong> {{ $product->rejection_reason }}</p>@endif
            <div class="rounded-2xl bg-accent/25 p-4 text-slate-950"><p class="text-xs font-semibold">Harga</p><p class="font-display text-2xl font-extrabold">Rp {{ number_format((int) $product->price, 0, ',', '.') }}</p></div>
            <div><h2 class="font-display text-lg font-bold text-ink">Deskripsi singkat</h2><p class="mt-2 text-sm leading-7 text-muted">{{ $product->short_description }}</p></div>
            <div><h2 class="font-display text-lg font-bold text-ink">Deskripsi lengkap</h2><p class="mt-2 whitespace-pre-line text-sm leading-7 text-muted">{{ $product->full_description }}</p></div>
            <p class="border-t border-slate-200 pt-4 text-sm text-muted dark:border-slate-700">Fee listing: <strong class="text-ink">Rp {{ number_format((int) $product->fee_amount, 0, ',', '.') }}</strong></p>
        </article>
        <aside class="space-y-4">
            <section class="dashboard-card">
                <h2 class="font-display text-lg font-bold text-ink">Bukti pembayaran</h2>
                @if ($product->payment_proof)
                    <p class="mt-2 text-sm leading-6 text-muted">Bukti Anda tersimpan di area privat dan tidak ditampilkan ke publik.</p>
                    <x-button :href="route('seller.product.payment-proof', $product)" variant="secondary" class="mt-4">Lihat bukti privat</x-button>
                @else
                    <p class="mt-2 text-sm text-muted">Belum ada bukti pembayaran yang tercatat.</p>
                @endif
            </section>
            <x-button :href="route('seller.product.index')" variant="quiet">← Kembali ke produk saya</x-button>
        </aside>
    </main>
</x-app-layout>
