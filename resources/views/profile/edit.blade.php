<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-primary dark:text-blue-200">Akun</p>
            <h1 class="mt-1 font-display text-2xl font-bold text-ink">Profil usaha & akun</h1>
        </div>
    </x-slot>

    <main class="page-container space-y-6 py-8 sm:py-10">
        <section class="dashboard-card"><div class="max-w-3xl">@include('profile.partials.update-profile-information-form')</div></section>
        <section class="dashboard-card"><div class="max-w-3xl">@include('profile.partials.update-password-form')</div></section>
        @if ($user->role !== 'seller')
            <section class="dashboard-card border-rose-200 dark:border-rose-900"><div class="max-w-3xl">@include('profile.partials.delete-user-form')</div></section>
        @endif
    </main>
</x-app-layout>
