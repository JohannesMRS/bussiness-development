<x-app-layout>
    <x-slot name="header"><div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Katalog</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">Tambah produk untuk seller</h1></div></x-slot>
    <main class="page-container max-w-4xl space-y-6 py-8 sm:py-10">
        <div class="rounded-2xl border border-primary/15 bg-primary/5 p-5 text-sm leading-6 text-muted dark:border-slate-700">
            Produk yang ditambahkan admin langsung berstatus <strong class="text-ink">approved</strong>, tanpa bukti listing fee dan dengan fee Rp 0.
        </div>
        <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data" class="dashboard-card space-y-5">
            @csrf
            <div>
                <label for="seller_id" class="mb-2 block text-sm font-semibold text-ink">Pilih seller</label>
                <select id="seller_id" name="seller_id" required class="form-control">
                    <option value="">Pilih seller</option>
                    @foreach ($sellers as $seller)
                        <option value="{{ $seller->id }}" @selected(old('seller_id') == $seller->id)>{{ $seller->bussiness_name ?: $seller->name }}</option>
                    @endforeach
                </select>
                @error('seller_id')<x-input-error :messages="[$message]" />@enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="category_id" class="mb-2 block text-sm font-semibold text-ink">Kategori</label>
                    <select id="category_id" name="category_id" required class="form-control">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<x-input-error :messages="[$message]" />@enderror
                </div>
                <div>
                    <label for="price" class="mb-2 block text-sm font-semibold text-ink">Harga (Rp)</label>
                    <input id="price" name="price" type="number" min="1" step="1" value="{{ old('price') }}" required class="form-control">
                    @error('price')<x-input-error :messages="[$message]" />@enderror
                </div>
            </div>

            <div>
                <label for="title" class="mb-2 block text-sm font-semibold text-ink">Nama produk</label>
                <input id="title" name="title" value="{{ old('title') }}" required class="form-control">
                @error('title')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="short_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi singkat</label>
                <textarea id="short_description" name="short_description" rows="3" required class="form-control">{{ old('short_description') }}</textarea>
                @error('short_description')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="full_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi lengkap</label>
                <textarea id="full_description" name="full_description" rows="6" required class="form-control">{{ old('full_description') }}</textarea>
                @error('full_description')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="image" class="mb-2 block text-sm font-semibold text-ink">Foto produk <span class="font-normal text-muted">(JPG/PNG/WebP, maksimal 2 MB)</span></label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" required class="form-control file:me-4 file:rounded-full file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-bold file:text-primary">
                @error('image')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-5 dark:border-slate-700">
                <x-button :href="route('admin.product.index')" variant="secondary">Batal</x-button>
                <x-button type="submit">Simpan produk</x-button>
            </div>
        </form>
    </main>
</x-app-layout>
