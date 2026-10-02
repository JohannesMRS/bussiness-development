<section>
    <header>
        <h2 class="font-display text-xl font-bold text-ink">Informasi akun</h2>
        <p class="mt-1 text-sm leading-6 text-muted">Perbarui nama dan email. Informasi usaha tampil pada profil publik.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 grid gap-5 sm:grid-cols-2">
        @csrf
        @method('patch')
        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="mt-2 text-xs text-muted">Email Anda belum terverifikasi. <button form="send-verification" class="font-semibold text-primary underline dark:text-blue-200">Kirim ulang email verifikasi</button></p>
                @if (session('status') === 'verification-link-sent')<p class="mt-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300">Tautan verifikasi baru telah dikirim.</p>@endif
            @endif
        </div>

        @if ($user->role === 'seller')
            <div>
                <x-input-label for="whatsapp_number" value="Nomor WhatsApp" />
                <x-text-input id="whatsapp_number" name="whatsapp_number" :value="old('whatsapp_number', $user->whatsapp_number)" required placeholder="08... atau 62..." />
                <x-input-error :messages="$errors->get('whatsapp_number')" />
            </div>
            <div>
                <x-input-label for="major" value="Program studi" />
                <select id="major" name="major" required class="form-control">
                    <option value="" @selected(! in_array(old('major', $user->major), \App\Models\User::MAJORS, true))>Pilih program studi</option>
                    @foreach (\App\Models\User::MAJORS as $major)
                        <option value="{{ $major }}" @selected(old('major', $user->major) === $major)>{{ $major }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('major')" />
            </div>
            <div>
                <x-input-label for="bussiness_name" value="Nama usaha" />
                <x-text-input id="bussiness_name" name="bussiness_name" :value="old('bussiness_name', $user->bussiness_name)" required />
                <x-input-error :messages="$errors->get('bussiness_name')" />
            </div>
            <div>
                <x-input-label for="avatar_url" value="URL avatar" />
                <x-text-input id="avatar_url" name="avatar_url" type="url" :value="old('avatar_url', $user->avatar_url)" />
                <x-input-error :messages="$errors->get('avatar_url')" />
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="bussiness_description" value="Deskripsi usaha" />
                <textarea id="bussiness_description" name="bussiness_description" rows="4" required class="form-control">{{ old('bussiness_description', $user->bussiness_description) }}</textarea>
                <x-input-error :messages="$errors->get('bussiness_description')" />
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-4 border-t border-slate-200 pt-5 dark:border-slate-700 sm:col-span-2">
            <x-primary-button>Simpan perubahan</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Perubahan tersimpan.</p>
            @endif
        </div>
    </form>
</section>
