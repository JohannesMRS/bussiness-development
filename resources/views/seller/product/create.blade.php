<x-app-layout>
    <x-slot name="header"><div><p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Produk saya</p><h1 class="mt-1 font-display text-2xl font-bold text-ink">Ajukan produk baru</h1></div></x-slot>
    <main class="page-container grid gap-6 py-8 sm:py-10 lg:grid-cols-[1fr_.68fr] lg:items-start">
        <form x-data="{ price: {{ (int) old('price', 0) }}, imagePreview: null }" method="POST" action="{{ route('seller.product.store') }}" enctype="multipart/form-data" class="dashboard-card space-y-5">
            @csrf
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
            <div><label for="title" class="mb-2 block text-sm font-semibold text-ink">Nama produk</label><input id="title" name="title" value="{{ old('title') }}" required class="form-control">@error('title')<x-input-error :messages="[$message]" />@enderror</div>
            <div><label for="short_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi singkat</label><textarea id="short_description" name="short_description" rows="3" required class="form-control">{{ old('short_description') }}</textarea>@error('short_description')<x-input-error :messages="[$message]" />@enderror</div>
            <div><label for="full_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi lengkap</label><textarea id="full_description" name="full_description" rows="6" required class="form-control">{{ old('full_description') }}</textarea>@error('full_description')<x-input-error :messages="[$message]" />@enderror</div>
            <div>
                <label for="price" class="mb-2 block text-sm font-semibold text-ink">Harga (Rp)</label>
                <input id="price" name="price" type="number" min="1" step="1" x-model.number="price" value="{{ old('price') }}" required class="form-control">
                @error('price')<x-input-error :messages="[$message]" />@enderror
                <p class="mt-2 text-sm text-muted">Estimasi listing fee 1%: <strong class="text-ink">Rp <span x-text="new Intl.NumberFormat('id-ID').format(Math.floor((Number(price)||0)/100)+(((Number(price)||0)%100)>=50?1:0))">0</span></strong> <span class="text-xs">(server menentukan nilai final)</span></p>
            </div>
            <div>
                <label for="image" class="mb-2 block text-sm font-semibold text-ink">Foto produk <span class="font-normal text-muted">(JPG/PNG/WebP, maksimal 2 MB)</span></label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" required class="form-control file:me-4 file:rounded-full file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-bold file:text-primary" @change="imagePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                <img x-show="imagePreview" x-cloak :src="imagePreview" alt="Pratinjau foto produk" class="mt-4 aspect-[4/3] max-h-64 w-full rounded-2xl object-cover">
                @error('image')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="payment_proof" class="mb-2 block text-sm font-semibold text-ink">Bukti transfer <span class="font-normal text-muted">(JPG/PNG/WebP, maksimal 2 MB)</span></label>
                <input id="payment_proof" name="payment_proof" type="file" accept="image/jpeg,image/png,image/webp" required class="form-control file:me-4 file:rounded-full file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-bold file:text-primary">
                @error('payment_proof')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-5 dark:border-slate-700"><x-button :href="route('seller.product.index')" variant="secondary">Batal</x-button><x-button type="submit">Ajukan untuk ditinjau</x-button></div>
        </form>

        <aside class="space-y-4 lg:sticky lg:top-28">
            <section class="rounded-3xl bg-primary p-6 text-white shadow-soft dark:text-slate-950">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 dark:bg-white/40"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v18M5 8h11a3 3 0 0 1 0 6H8a3 3 0 0 0 0 6h11" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span>
                <h2 class="mt-4 font-display text-xl font-bold">Biaya listing & rekening</h2>
                <p class="mt-2 text-sm leading-6 text-white/80 dark:text-slate-900/80">Biaya satu kali sebesar 1% dari harga. Pembayaran dilakukan di luar situs setelah transfer.</p>
                <div class="mt-5 rounded-2xl bg-white/10 p-4 text-sm leading-6 dark:bg-white/40">
                    <p class="font-bold">{{ $bankDetails['name'] }}</p>
                    <p>{{ $bankDetails['account_name'] }}</p>
                    <p class="font-mono font-bold tracking-wider">{{ $bankDetails['account_number'] }}</p>
                    @if (config('bizdev.is_demo_bank'))<p class="mt-3 rounded-xl bg-accent/30 px-3 py-2 text-xs font-extrabold text-slate-950">{{ __('public.common.demo_warning') }}</p>@endif
                </div>
            </section>
            <section class="dashboard-card">
                <h2 class="font-display text-lg font-bold text-ink">Setelah pengajuan</h2>
                <ol class="mt-4 grid gap-3 text-sm leading-6 text-muted">
                    <li class="flex gap-3"><span class="font-black text-primary dark:text-blue-200">01</span><span>Admin meninjau informasi produk dan bukti transfer.</span></li>
                    <li class="flex gap-3"><span class="font-black text-primary dark:text-blue-200">02</span><span>Produk tampil setelah disetujui.</span></li>
                    <li class="flex gap-3"><span class="font-black text-primary dark:text-blue-200">03</span><span>Pembeli menghubungi Anda langsung melalui WhatsApp.</span></li>
                </ol>
            </section>
        </aside>
    </main>
</x-app-layout>
