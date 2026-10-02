<x-app-layout>
    <x-slot name="header"><div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Produk saya</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">Edit produk</h1></div></x-slot>
    <main class="page-container grid gap-6 py-8 sm:py-10 lg:grid-cols-[1fr_.68fr] lg:items-start">
        <form x-data="{ price: {{ (int) old('price', $product->price) }}, imagePreview: null }" method="POST" action="{{ route('seller.product.update', $product) }}" enctype="multipart/form-data" class="dashboard-card space-y-5">
            @csrf @method('PUT')
            <div class="flex flex-wrap items-center gap-2"><x-badge :variant="match($product->status) {'approved' => 'success', 'pending' => 'warning', default => 'danger'}">{{ match($product->status) {'approved' => 'Disetujui', 'pending' => 'Menunggu tinjauan', default => 'Ditolak'} }}</x-badge>@if ($product->rejection_reason)<x-badge variant="danger">{{ $product->rejection_reason }}</x-badge>@endif</div>
            <div>
                <label for="category_id" class="mb-2 block text-sm font-semibold text-ink">Kategori</label>
                <select id="category_id" name="category_id" required class="form-control">@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select>
                @error('category_id')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div><label for="title" class="mb-2 block text-sm font-semibold text-ink">Nama produk</label><input id="title" name="title" value="{{ old('title', $product->title) }}" required class="form-control">@error('title')<x-input-error :messages="[$message]" />@enderror</div>
            <div><label for="short_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi singkat</label><textarea id="short_description" name="short_description" rows="3" required class="form-control">{{ old('short_description', $product->short_description) }}</textarea>@error('short_description')<x-input-error :messages="[$message]" />@enderror</div>
            <div><label for="full_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi lengkap</label><textarea id="full_description" name="full_description" rows="6" required class="form-control">{{ old('full_description', $product->full_description) }}</textarea>@error('full_description')<x-input-error :messages="[$message]" />@enderror</div>
            <div>
                <label for="price" class="mb-2 block text-sm font-semibold text-ink">Harga (Rp)</label>
                <input id="price" name="price" type="number" min="1" step="1" x-model.number="price" value="{{ old('price', $product->price) }}" required class="form-control">
                @error('price')<x-input-error :messages="[$message]" />@enderror
                <p class="mt-2 text-sm text-muted">Estimasi listing fee 1%: <strong class="text-ink">Rp <span x-text="new Intl.NumberFormat('id-ID').format(Math.floor((Number(price)||0)/100)+(((Number(price)||0)%100)>=50?1:0))">{{ number_format((int) $product->fee_amount, 0, ',', '.') }}</span></strong> <span class="text-xs">(server menentukan nilai final)</span></p>
            </div>
            <div>
                <label for="image" class="mb-2 block text-sm font-semibold text-ink">Ganti foto <span class="font-normal text-muted">(opsional; JPG/PNG/WebP, maksimal 2 MB)</span></label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control file:me-4 file:rounded-full file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-bold file:text-primary" @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                <img x-show="imagePreview" x-cloak :src="imagePreview" alt="Pratinjau foto baru" class="mt-4 aspect-[4/3] max-h-64 w-full rounded-2xl object-cover">
                @error('image')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="payment_proof" class="mb-2 block text-sm font-semibold text-ink">Bukti transfer baru <span class="font-normal text-muted">(wajib jika harga berubah; JPG/PNG/WebP, maksimal 2 MB)</span></label>
                <input id="payment_proof" name="payment_proof" type="file" accept="image/jpeg,image/png,image/webp" class="form-control file:me-4 file:rounded-full file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-bold file:text-primary">
                @error('payment_proof')<x-input-error :messages="[$message]" />@enderror
                @if ($product->payment_proof)<a href="{{ route('seller.product.payment-proof', $product) }}" class="mt-2 inline-flex min-h-11 items-center text-sm font-bold text-primary dark:text-blue-200">Lihat bukti yang tersimpan →</a>@endif
            </div>
            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-5 dark:border-slate-700"><x-button :href="route('seller.product.index')" variant="secondary">Batal</x-button><x-button type="submit">Simpan perubahan</x-button></div>
        </form>

        <aside class="space-y-4 lg:sticky lg:top-28">
            <section class="dashboard-card">
                <h2 class="font-display text-xl font-bold text-ink">Informasi listing</h2>
                <p class="mt-3 text-sm leading-6 text-muted">Fee saat ini <strong class="text-ink">Rp {{ number_format((int) $product->fee_amount, 0, ',', '.') }}</strong>. Perubahan harga perlu bukti transfer baru dan akan diajukan ulang untuk ditinjau.</p>
            </section>
            <section @class(['dashboard-card', 'border-amber-300 bg-amber-50 dark:border-amber-900 dark:bg-amber-950/40' => config('bizdev.is_demo_bank')])>
                @if (config('bizdev.is_demo_bank'))<x-badge variant="warning">{{ __('public.common.demo_label') }}</x-badge>@endif
                <h2 class="mt-4 font-display text-xl font-bold text-ink">Informasi rekening</h2>
                <p class="mt-3 text-sm font-semibold text-ink">{{ $bankDetails['name'] }}</p>
                <p class="text-sm text-muted">{{ $bankDetails['account_name'] }}</p>
                <p class="font-mono text-lg font-bold tracking-wider text-ink">{{ $bankDetails['account_number'] }}</p>
                @if (config('bizdev.is_demo_bank'))<p class="mt-3 text-xs leading-5 text-amber-900 dark:text-amber-200">{{ __('public.common.demo_warning') }}</p>@else<p class="mt-3 text-xs leading-5 text-muted">Transfer hanya setelah memahami biaya listing. Simpan bukti transfer untuk diajukan ke admin.</p>@endif
            </section>
        </aside>
    </main>
</x-app-layout>
