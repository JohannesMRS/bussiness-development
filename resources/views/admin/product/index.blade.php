<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Panel admin</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">Manajemen produk</h1></div>
            <x-button :href="route('admin.product.create')">+ Tambah produk</x-button>
        </div>
    </x-slot>
    <main class="page-container space-y-6 py-8 sm:py-10">
        @if (session('success'))<p role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">{{ session('success') }}</p>@endif
        <section class="dashboard-card">
            <form method="GET" action="{{ route('admin.product.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="w-full sm:max-w-xs">
                    <label for="status" class="mb-2 block text-sm font-semibold text-ink">Filter status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Semua status</option>
                        @foreach (['pending' => 'Menunggu tinjauan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $item => $label)
                            <option value="{{ $item }}" @selected($status === $item)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-button type="submit" variant="secondary">Terapkan filter</x-button>
            </form>
        </section>

        @if ($errors->has('payment_proof'))
            <p role="alert" class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-900 dark:border-amber-900 dark:bg-amber-950/50 dark:text-amber-200">{{ $errors->first('payment_proof') }}</p>
        @endif

        <section class="grid gap-4">
            @forelse ($products as $product)
                <article x-data="{ rejectOpen: false }" class="dashboard-card">
                    <div class="flex flex-col justify-between gap-5 lg:flex-row lg:items-start">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-badge :variant="match($product->status) {'approved' => 'success', 'pending' => 'warning', default => 'danger'}">{{ match($product->status) {'approved' => 'Disetujui', 'pending' => 'Menunggu tinjauan', default => 'Ditolak'} }}</x-badge>
                                @if ($product->is_featured)<x-badge variant="primary">Rekomendasi</x-badge>@endif
                                <x-badge>{{ $product->category->name }}</x-badge>
                            </div>
                            <h2 class="mt-3 font-display text-xl font-bold text-ink"><a href="{{ route('admin.product.show', $product) }}" class="hover:text-primary">{{ $product->title }}</a></h2>
                            <p class="mt-1 text-sm text-muted">{{ $product->seller->bussiness_name ?: $product->seller->name }}</p>
                            <p class="mt-3 text-sm leading-6 text-muted">{{ $product->short_description }}</p>
                            <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold">
                                <span class="rounded-lg bg-accent/25 px-3 py-2 text-slate-950">Rp {{ number_format((int) $product->price, 0, ',', '.') }}</span>
                                <span class="rounded-lg bg-slate-100 px-3 py-2 text-slate-700 dark:bg-slate-800 dark:text-slate-200">Fee Rp {{ number_format((int) $product->fee_amount, 0, ',', '.') }}</span>
                                <span class="rounded-lg bg-slate-100 px-3 py-2 text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $product->views_count }} dilihat</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 lg:max-w-sm lg:justify-end">
                            <x-button :href="route('admin.product.edit', $product)" variant="secondary">Edit</x-button>
                            @if ($product->payment_proof)
                                <x-button :href="route('admin.product.payment-proof', $product)" variant="quiet">Bukti transfer</x-button>
                            @endif
                            <form method="POST" action="{{ route('admin.product.approve', $product) }}">@csrf<x-button type="submit">Setujui</x-button></form>
                            <x-button type="button" variant="secondary" @click="rejectOpen = true">Tolak</x-button>
                            <form method="POST" action="{{ route('admin.product.featured', $product) }}">
                                @csrf @method('PATCH')
                                <x-button type="submit" variant="secondary">{{ $product->is_featured ? 'Hapus rekomendasi' : 'Jadikan rekomendasi' }}</x-button>
                            </form>
                            <form method="POST" action="{{ route('admin.product.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini secara permanen?')">
                                @csrf @method('DELETE')
                                <x-button type="submit" variant="danger">Hapus</x-button>
                            </form>
                        </div>
                    </div>

                    <div x-show="rejectOpen" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="reject-title-{{ $product->id }}" @keydown.escape.window="rejectOpen = false">
                        <div @click.outside="rejectOpen = false" class="w-full max-w-lg rounded-3xl bg-surface p-6 shadow-soft sm:p-8">
                            <h3 id="reject-title-{{ $product->id }}" class="font-display text-2xl font-bold text-ink">Tolak pengajuan produk</h3>
                            <p class="mt-2 text-sm text-muted">{{ $product->title }}</p>
                            <form method="POST" action="{{ route('admin.product.reject', $product) }}" class="mt-5 space-y-4">
                                @csrf
                                <div>
                                    <label for="rejection_reason_{{ $product->id }}" class="mb-2 block text-sm font-semibold text-ink">Alasan penolakan</label>
                                    <textarea id="rejection_reason_{{ $product->id }}" name="rejection_reason" required rows="4" class="form-control" placeholder="Jelaskan hal yang perlu diperbaiki"></textarea>
                                </div>
                                <div class="flex flex-wrap justify-end gap-2">
                                    <x-button type="button" variant="secondary" @click="rejectOpen = false">Batal</x-button>
                                    <x-button type="submit" variant="danger">Kirim penolakan</x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <x-empty-state title="Belum ada produk" description="Produk dan pengajuan seller akan muncul di sini." :action="'Tambah produk'" :href="route('admin.product.create')" />
            @endforelse
        </section>
        {{ $products->links() }}
    </main>
</x-app-layout>
