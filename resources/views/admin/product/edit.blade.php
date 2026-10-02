<x-app-layout>
    <x-slot name="header"><div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Katalog</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">Edit produk</h1></div></x-slot>
    <main class="page-container max-w-4xl space-y-6 py-8 sm:py-10">
        <div class="dashboard-card flex flex-wrap items-center justify-between gap-3">
            <div><h2 class="font-display text-xl font-bold text-ink">{{ $product->title }}</h2><p class="mt-1 text-sm text-muted">{{ $product->seller->bussiness_name ?: $product->seller->name }}</p></div>
            <x-badge :variant="match($product->status) {'approved' => 'success', 'pending' => 'warning', default => 'danger'}">{{ match($product->status) {'approved' => 'Disetujui', 'pending' => 'Menunggu tinjauan', default => 'Ditolak'} }}</x-badge>
        </div>
        @if ($product->payment_proof)
            <p><x-button :href="route('admin.product.payment-proof', $product)" variant="secondary">Unduh bukti transfer privat</x-button></p>
        @endif
        <form method="POST" action="{{ route('admin.product.update', $product) }}" enctype="multipart/form-data" class="dashboard-card space-y-5">
            @csrf @method('PUT')
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label for="category_id" class="mb-2 block text-sm font-semibold text-ink">Kategori</label><select id="category_id" name="category_id" required class="form-control">@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id')<x-input-error :messages="[$message]" />@enderror</div>
                <div><label for="price" class="mb-2 block text-sm font-semibold text-ink">Harga (Rp)</label><input id="price" name="price" type="number" min="1" step="1" value="{{ old('price', $product->price) }}" required class="form-control">@error('price')<x-input-error :messages="[$message]" />@enderror</div>
            </div>
            <div><label for="title" class="mb-2 block text-sm font-semibold text-ink">Nama produk</label><input id="title" name="title" value="{{ old('title', $product->title) }}" required class="form-control">@error('title')<x-input-error :messages="[$message]" />@enderror</div>
            <div><label for="short_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi singkat</label><textarea id="short_description" name="short_description" rows="3" required class="form-control">{{ old('short_description', $product->short_description) }}</textarea>@error('short_description')<x-input-error :messages="[$message]" />@enderror</div>
            <div><label for="full_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi lengkap</label><textarea id="full_description" name="full_description" rows="6" required class="form-control">{{ old('full_description', $product->full_description) }}</textarea>@error('full_description')<x-input-error :messages="[$message]" />@enderror</div>
            <div><label for="image" class="mb-2 block text-sm font-semibold text-ink">Ganti foto <span class="font-normal text-muted">(opsional; JPG/PNG/WebP, maksimal 2 MB)</span></label><input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control file:me-4 file:rounded-full file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-bold file:text-primary">@error('image')<x-input-error :messages="[$message]" />@enderror</div>
            <div class="flex justify-end gap-3 border-t border-slate-200 pt-5 dark:border-slate-700"><x-button :href="route('admin.product.index')" variant="secondary">Kembali</x-button><x-button type="submit">Simpan perubahan</x-button></div>
        </form>
    </main>
</x-app-layout>
