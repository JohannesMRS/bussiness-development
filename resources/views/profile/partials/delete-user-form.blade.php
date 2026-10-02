<section class="space-y-5">
    <header>
        <h2 class="font-display text-xl font-bold text-ink">Hapus akun</h2>
        <p class="mt-1 text-sm leading-6 text-muted">Penghapusan akun bersifat permanen. Pastikan akun yang dikelola tidak lagi dibutuhkan.</p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">Hapus akun</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5 p-6 sm:p-8">
            @csrf
            @method('delete')
            <div>
                <h3 class="font-display text-xl font-bold text-ink">Yakin ingin menghapus akun?</h3>
                <p class="mt-2 text-sm leading-6 text-muted">Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi untuk konfirmasi.</p>
            </div>
            <div>
                <x-input-label for="password" value="Kata sandi" class="sr-only" />
                <x-text-input id="password" name="password" type="password" placeholder="Kata sandi" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-danger-button>Hapus akun permanen</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
