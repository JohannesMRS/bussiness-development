@extends('layouts.public')

@section('title', $product->title.' | Bussiness Development')
@section('meta_description', $product->short_description)
@section('og_image', \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image_url) ? url('/storage/'.$product->image_url) : url('/storage/images/products/placeholder.svg'))
@section('canonical', route('products.show', $product))

@section('content')
    @php
        $productImageUrl = \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image_url)
            ? url('/storage/'.$product->image_url)
            : url('/public/images/comingsoon.png');
    @endphp
    <section class="page-container py-8 sm:py-12">
        <nav aria-label="Breadcrumb" class="mb-7 flex flex-wrap items-center gap-2 text-sm text-muted">
            <a href="{{ route('home') }}" class="hover:text-primary">{{ __('public.nav.home') }}</a><span aria-hidden="true">/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-primary">{{ __('public.nav.catalog') }}</a><span aria-hidden="true">/</span>
            <span class="max-w-[15rem] truncate font-semibold text-ink" aria-current="page">{{ $product->title }}</span>
        </nav>
        <article class="grid gap-8 lg:grid-cols-[1.1fr_.9fr] lg:gap-12">
            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-surface shadow-card dark:border-slate-700">
                <img src="{{ $productImageUrl }}" alt="{{ $product->title }}" width="960" height="720" fetchpriority="high" class="aspect-[4/3] h-full w-full object-cover">
            </div>
            <div class="flex flex-col py-1 sm:py-4">
                <x-badge variant="primary">{{ $product->category->name }}</x-badge>
                <h1 class="mt-4 font-display text-4xl font-bold leading-tight text-ink sm:text-5xl">{{ $product->title }}</h1>
                <p class="mt-4 text-base leading-7 text-muted">{{ $product->short_description }}</p>
                <div class="mt-6 inline-flex w-fit flex-col rounded-2xl bg-accent/25 px-5 py-3 text-slate-950">
                    <span class="text-xs font-semibold">{{ __('public.product.price') }}</span>
                    <strong class="font-display text-2xl">Rp {{ number_format((int) $product->price, 0, ',', '.') }}</strong>
                </div>
                <div class="mt-8 hidden sm:block">
                    @if ($product->whatsapp_url)
                        <x-button :href="$product->whatsapp_url" target="_blank" rel="noopener" class="w-full px-6 py-4 text-base" :aria-label="__('public.product.order_for', ['name' => $product->title])">
                            {{ __('public.product.order_whatsapp') }}
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </x-button>
                    @endif
                </div>
                <div class="mt-8 border-t border-slate-200 pt-6 dark:border-slate-700">
                    <h2 class="font-display text-xl font-bold text-ink">{{ __('public.product.seller_info') }}</h2>
                    <div class="mt-4 flex items-start gap-4">
                        @if ($product->seller->avatar_url)
                            <img src="{{ $product->seller->avatar_url }}" alt="{{ __('public.seller.avatar_alt', ['name' => $product->seller->name]) }}" width="56" height="56" loading="lazy" class="h-14 w-14 rounded-2xl object-cover">
                        @else
                            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 font-bold text-primary dark:text-blue-200" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($product->seller->bussiness_name ?: $product->seller->name, 0, 1)) }}</span>
                        @endif
                        <div class="min-w-0">
                            <p class="font-bold text-ink">{{ $product->seller->bussiness_name ?: $product->seller->name }}</p>
                            <p class="mt-1 text-sm text-muted">{{ $product->seller->major }}</p>
                            <a href="{{ route('sellers.show', $product->seller->id) }}" class="mt-2 inline-flex min-h-11 items-center text-sm font-bold text-primary hover:text-secondary dark:text-blue-200">{{ __('public.product.view_profile') }} <span class="ms-2" aria-hidden="true">→</span></a>
                        </div>
                    </div>
                </div>
                <div class="mt-7 border-t border-slate-200 pt-6 dark:border-slate-700">
                    <h2 class="font-display text-xl font-bold text-ink">{{ __('public.product.description') }}</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-7 text-muted">{{ $product->full_description }}</p>
                </div>
            </div>
        </article>
    </section>

    <section class="page-container pb-24 pt-8 sm:pb-16 sm:pt-12" data-reveal>
        <div class="mb-7">
            <span class="eyebrow">{{ __('public.product.related') }}</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-ink">{{ __('public.product.related') }}</h2>
            <p class="mt-2 text-sm text-muted">{{ __('public.product.related_lead') }}</p>
        </div>
        @if ($relatedProducts->isEmpty())
            <x-empty-state :title="__('public.common.empty_products_title')" :description="__('public.common.empty_products_body')" />
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($relatedProducts as $relatedProduct)
                    <x-product-card :product="$relatedProduct" />
                @endforeach
            </div>
        @endif
    </section>

    @if ($product->whatsapp_url)
        <div class="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-surface/95 p-3 backdrop-blur sm:hidden dark:border-slate-700">
            <div class="page-container"><x-button :href="$product->whatsapp_url" target="_blank" rel="noopener" class="w-full" :aria-label="__('public.product.order_for', ['name' => $product->title])">{{ __('public.product.order_whatsapp') }}</x-button></div>
        </div>
    @endif
@endsection
