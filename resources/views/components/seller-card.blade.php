@props(['seller'])
@php
    $avatarInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($seller->bussiness_name ?: $seller->name, 0, 1));
@endphp
<article {{ $attributes->class(['group flex h-full flex-col rounded-3xl border border-slate-200 bg-surface p-6 shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-soft dark:border-slate-700']) }}>
    <div class="flex items-center gap-4">
        @if ($seller->avatar_url)
            <img src="{{ $seller->avatar_url }}" alt="{{ __('public.seller.avatar_alt', ['name' => $seller->name]) }}" width="64" height="64" loading="lazy" class="h-16 w-16 rounded-2xl object-cover ring-2 ring-primary/10">
        @else
            <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary text-xl font-bold text-white dark:text-slate-950" aria-hidden="true">{{ $avatarInitial }}</span>
        @endif
        <div class="min-w-0">
            <h2 class="truncate font-display text-xl font-bold text-ink"><a class="hover:text-secondary" href="{{ route('sellers.show', $seller->id) }}">{{ $seller->bussiness_name ?: $seller->name }}</a></h2>
            <p class="mt-1 text-sm text-muted">{{ $seller->major }}</p>
        </div>
    </div>
    <p class="mt-5 line-clamp-3 flex-1 text-sm leading-6 text-muted">{{ $seller->bussiness_description }}</p>
    <div class="mt-5 flex items-center justify-between gap-3 border-t border-slate-100 pt-4 dark:border-slate-700">
        <x-badge variant="primary">{{ trans_choice('public.seller.products_count', $seller->approved_products_count, ['count' => $seller->approved_products_count]) }}</x-badge>
        <a href="{{ route('sellers.show', $seller->id) }}" class="inline-flex min-h-11 items-center gap-2 rounded-full px-3 text-sm font-bold text-primary hover:bg-primary/5 dark:text-blue-200">
            {{ __('public.seller.view_profile') }}
            <span aria-hidden="true">→</span>
        </a>
    </div>
</article>
