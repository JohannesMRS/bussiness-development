@extends('layouts.public')

@section('title', ($seller->bussiness_name ?: $seller->name).' | Bussiness Development')
@section('meta_description', $seller->bussiness_description ?: __('public.seller.business'))

@section('content')
    <section class="bg-hero-glow">
        <div class="page-container py-12 sm:py-16">
            <a href="{{ route('sellers.index') }}" class="inline-flex min-h-11 items-center gap-2 text-sm font-bold text-primary hover:text-secondary dark:text-blue-200">← {{ __('public.nav.sellers') }}</a>
            <div class="mt-7 grid items-center gap-8 rounded-[2rem] border border-slate-200 bg-surface p-6 shadow-soft sm:p-9 lg:grid-cols-[auto_1fr_auto] dark:border-slate-700">
                @if ($seller->avatar_url)
                    <img src="{{ $seller->avatar_url }}" alt="{{ __('public.seller.avatar_alt', ['name' => $seller->name]) }}" width="144" height="144" loading="lazy" class="h-28 w-28 rounded-3xl object-cover sm:h-36 sm:w-36">
                @else
                    <span class="flex h-28 w-28 items-center justify-center rounded-3xl bg-primary text-4xl font-black text-white sm:h-36 sm:w-36 dark:text-slate-950" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($seller->bussiness_name ?: $seller->name, 0, 1)) }}</span>
                @endif
                <div>
                    <x-badge variant="primary">{{ __('public.seller.business') }}</x-badge>
                    <h1 class="mt-3 font-display text-3xl font-bold text-ink sm:text-4xl">{{ $seller->bussiness_name ?: $seller->name }}</h1>
                    <p class="mt-2 text-sm font-semibold text-primary dark:text-blue-200">{{ $seller->name }} <span class="px-1 text-muted">·</span> {{ $seller->major }}</p>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-muted">{{ $seller->bussiness_description }}</p>
                </div>
                @if ($seller->whatsapp_url)
                    <x-button :href="$seller->whatsapp_url" target="_blank" rel="noopener" class="w-full lg:w-auto">{{ __('public.seller.contact') }}</x-button>
                @endif
            </div>
        </div>
    </section>

    <section class="page-container py-12 sm:py-16">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <span class="eyebrow">{{ trans_choice('public.seller.products_count', $products->total(), ['count' => $products->total()]) }}</span>
                <h2 class="mt-4 font-display text-3xl font-bold text-ink">{{ __('public.seller.products_heading') }}</h2>
            </div>
        </div>
        @if ($products->isEmpty())
            <x-empty-state :title="__('public.common.empty_products_title')" :description="__('public.common.empty_products_body')" />
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-10">{{ $products->links() }}</div>
        @endif
    </section>
@endsection
