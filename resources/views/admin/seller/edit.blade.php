<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Manajemen seller</p>
            <h1 class="mt-1 font-display text-2xl font-bold text-ink">Edit {{ $seller->name }}</h1>
        </div>
    </x-slot>
    <main class="page-container max-w-4xl space-y-6 py-8 sm:py-10">
        @if (session('success'))
            <p role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200">{{ session('success') }}</p>
        @endif
        <form method="POST" action="{{ route('admin.seller.update', $seller) }}" class="dashboard-card grid gap-5 sm:grid-cols-2">
            @csrf @method('PUT')
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-ink">Nama lengkap</label>
                <input id="name" name="name" value="{{ old('name', $seller->name) }}" required class="form-control">
                @error('name')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-ink">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $seller->email) }}" required class="form-control">
                @error('email')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="whatsapp_number" class="mb-2 block text-sm font-semibold text-ink">Nomor WhatsApp</label>
                <input id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $seller->whatsapp_number) }}" required class="form-control">
                @error('whatsapp_number')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="major" class="mb-2 block text-sm font-semibold text-ink">Program studi</label>
                <select id="major" name="major" required class="form-control">
                    <option value="" @selected(! in_array(old('major', $seller->major), $majors, true))>Pilih program studi</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major }}" @selected(old('major', $seller->major) === $major)>{{ $major }}</option>
                    @endforeach
                </select>
                @error('major')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="bussiness_name" class="mb-2 block text-sm font-semibold text-ink">Nama usaha</label>
                <input id="bussiness_name" name="bussiness_name" value="{{ old('bussiness_name', $seller->bussiness_name) }}" required class="form-control">
                @error('bussiness_name')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="avatar_url" class="mb-2 block text-sm font-semibold text-ink">URL avatar</label>
                <input id="avatar_url" name="avatar_url" type="url" value="{{ old('avatar_url', $seller->avatar_url) }}" class="form-control">
                @error('avatar_url')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="bussiness_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi usaha</label>
                <textarea id="bussiness_description" name="bussiness_description" rows="4" required class="form-control">{{ old('bussiness_description', $seller->bussiness_description) }}</textarea>
                @error('bussiness_description')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div class="flex justify-end border-t border-slate-200 pt-5 dark:border-slate-700 sm:col-span-2"><x-button type="submit">Simpan profil</x-button></div>
        </form>
        <form method="POST" action="{{ route('admin.seller.password', $seller) }}" class="dashboard-card grid gap-5 sm:grid-cols-2">
            @csrf @method('PUT')
            <div class="sm:col-span-2"><h2 class="font-display text-xl font-bold text-ink">Reset password</h2><p class="mt-1 text-sm text-muted">Atur kata sandi baru untuk akun ini.</p></div>
            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-ink">Password baru</label>
                <input id="password" name="password" type="password" required class="form-control">
                @error('password')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-ink">Konfirmasi password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control">
            </div>
            <div class="flex justify-end border-t border-slate-200 pt-5 dark:border-slate-700 sm:col-span-2"><x-button type="submit">Reset password</x-button></div>
        </form>
    </main>
</x-app-layout>