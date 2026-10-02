<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Panel admin</p>
            <h1 class="mt-1 font-display text-2xl font-bold text-ink">Buat akun seller</h1>
        </div>
    </x-slot>
    <main class="page-container max-w-4xl py-8 sm:py-10">
        <form method="POST" action="{{ route('admin.seller.store') }}" class="dashboard-card grid gap-5 sm:grid-cols-2">
            @csrf
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-ink">Nama lengkap</label>
                <input id="name" name="name" value="{{ old('name') }}" required class="form-control">
                @error('name')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-ink">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="form-control">
                @error('email')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-ink">Password awal</label>
                <input id="password" name="password" type="password" required class="form-control">
                @error('password')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-ink">Konfirmasi password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control">
            </div>
            <div>
                <label for="whatsapp_number" class="mb-2 block text-sm font-semibold text-ink">Nomor WhatsApp</label>
                <input id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required class="form-control" placeholder="08... atau 62...">
                @error('whatsapp_number')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="major" class="mb-2 block text-sm font-semibold text-ink">Program studi</label>
                <select id="major" name="major" required class="form-control">
                    <option value="">Pilih program studi</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major }}" @selected(old('major') === $major)>{{ $major }}</option>
                    @endforeach
                </select>
                @error('major')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="bussiness_name" class="mb-2 block text-sm font-semibold text-ink">Nama usaha</label>
                <input id="bussiness_name" name="bussiness_name" value="{{ old('bussiness_name') }}" required class="form-control">
                @error('bussiness_name')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div>
                <label for="avatar_url" class="mb-2 block text-sm font-semibold text-ink">URL avatar <span class="font-normal text-muted">(opsional)</span></label>
                <input id="avatar_url" name="avatar_url" type="url" value="{{ old('avatar_url') }}" class="form-control">
                @error('avatar_url')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="bussiness_description" class="mb-2 block text-sm font-semibold text-ink">Deskripsi usaha</label>
                <textarea id="bussiness_description" name="bussiness_description" rows="4" required class="form-control">{{ old('bussiness_description') }}</textarea>
                @error('bussiness_description')<x-input-error :messages="[$message]" />@enderror
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-200 pt-5 dark:border-slate-700 sm:col-span-2">
                <x-button :href="route('admin.seller.index')" variant="secondary">Batal</x-button>
                <x-button type="submit">Buat akun</x-button>
            </div>
        </form>
    </main>
</x-app-layout>