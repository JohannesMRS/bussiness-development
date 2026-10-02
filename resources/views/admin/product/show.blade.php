<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Detail katalog</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">{{ $product->title }}</h1></div>
            <x-button :href="route('admin.product.edit', $product)" variant="secondary">Edit produk</x-button>
        </div>
    </x-slot>
    <main class="page-container grid gap-6 py-8 sm:py-10 lg:grid-cols-[1fr_.85fr]">
        <article class="dashboard-card space-y-5">
            <div class="flex flex-wrap gap-2"><x-badge>{{ $product->category->name }}</x-badge><x-badge :variant="match($product->status) {'approved' => 'success', 'pending' => 'warning', default => 'danger'}">{{ match($product->status) {'approved' => 'Disetujui', 'pending' => 'Menunggu tinjauan', default => 'Ditolak'} }}</x-badge>@if($product->is_featured)<x-badge variant="primary">Rekomendasi</x-badge>@endif</div>
            <p class="text-sm leading-7 text-muted">{{ $product->short_description }}</p>
            <div class="rounded-2xl bg-accent/25 p-4 text-slate-950"><p class="text-xs font-semibold">Harga</p><p class="font-display text-2xl font-extrabold">Rp {{ number_format((int) $product->price, 0, ',', '.') }}</p></div>
            <div><h2 class="font-display text-lg font-bold text-ink">Deskripsi lengkap</h2><p class="mt-2 whitespace-pre-line text-sm leading-7 text-muted">{{ $product->full_description }}</p></div>
            <div class="grid gap-3 border-t border-slate-200 pt-5 sm:grid-cols-2 dark:border-slate-700">
                <div><p class="text-xs font-semibold text-muted">Fee listing</p><p class="mt-1 font-bold text-ink">Rp {{ number_format((int) $product->fee_amount, 0, ',', '.') }}</p></div>
                <div><p class="text-xs font-semibold text-muted">Jumlah dilihat</p><p class="mt-1 font-bold text-ink">{{ $product->views_count }}</p></div>
            </div>
            @if ($product->rejection_reason)<p class="rounded-xl bg-rose-50 p-4 text-sm text-rose-800 dark:bg-rose-950/40 dark:text-rose-200"><strong>Alasan penolakan:</strong> {{ $product->rejection_reason }}</p>@endif
        </article>
        <aside class="space-y-5">
            <section class="dashboard-card">
                <h2 class="font-display text-lg font-bold text-ink">Informasi seller</h2>
                <p class="mt-3 font-semibold text-ink">{{ $product->seller->bussiness_name ?: $product->seller->name }}</p>
                <p class="mt-1 text-sm text-muted">{{ $product->seller->name }} · {{ $product->seller->major }}</p>
                <p class="mt-3 text-sm leading-6 text-muted">{{ $product->seller->bussiness_description }}</p>
            </section>
            <section class="dashboard-card">
                <h2 class="font-display text-lg font-bold text-ink">Bukti listing</h2>
                @if ($product->payment_proof)
                    <p class="mt-2 text-sm text-muted">Berkas disimpan privat dan hanya dapat diakses admin atau seller pemilik.</p>
                    <x-button :href="route('admin.product.payment-proof', $product)" variant="secondary" class="mt-4">Unduh bukti privat</x-button>
                @else
                    <p class="mt-2 text-sm text-muted">Tidak ada bukti transfer (produk dibuat admin atau berkas belum tersedia).</p>
                @endif
            </section>
            <x-button :href="route('admin.product.index')" variant="quiet">← Kembali ke daftar produk</x-button>
        </aside>
    </main>
</x-app-layout>
