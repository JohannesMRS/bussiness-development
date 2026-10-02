@extends('layouts.public')

@section('title', __('public.nav.home').' | BizDev HMPS MI Polmed')
@section('meta_description', __('public.brand.description'))

@section('content')
    <section class="relative isolate overflow-hidden bg-hero-glow">
        <div class="page-container grid min-h-[620px] items-center gap-12 py-16 sm:py-20 lg:grid-cols-[1.05fr_.95fr] lg:py-24">
            <div class="relative z-10" data-reveal>
                <span class="eyebrow"><span class="h-2 w-2 rounded-full bg-accent"></span>{{ __('public.home.eyebrow') }}</span>
                <h1 class="mt-6 max-w-3xl font-display text-5xl font-bold leading-[1.06] tracking-tight text-ink sm:text-6xl lg:text-7xl">{{ __('public.home.title') }}</h1>
                <p class="mt-6 max-w-xl text-base leading-8 text-muted sm:text-lg">{{ __('public.home.lead') }}</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <x-button :href="route('catalog.index')" class="px-6 py-3.5">
                        {{ __('public.home.cta') }}
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </x-button>
                    <x-button :href="route('about')" variant="secondary" class="px-6 py-3.5">{{ __('public.home.seller_cta') }}</x-button>
                </div>
                <p class="mt-5 inline-flex items-center gap-2 text-xs font-medium text-muted">
                    <svg class="h-4 w-4 text-primary" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m12 3 8 4v5c0 5-3.4 8-8 9-4.6-1-8-4-8-9V7l8-4Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('public.home.trust_note') }}
                </p>
            </div>

            <div class="relative mx-auto w-full max-w-xl" data-reveal>
                <div class="absolute -right-5 -top-6 h-32 w-32 rounded-full bg-accent/35 blur-3xl" aria-hidden="true"></div>
                <div class="absolute -bottom-8 -left-7 h-40 w-40 rounded-full bg-secondary/20 blur-3xl" aria-hidden="true"></div>
                <div class="relative rotate-1 rounded-[2rem] border border-white/70 bg-surface p-3 shadow-soft dark:border-slate-700 sm:p-5">
                    @if ($newProducts->isNotEmpty())
                        @php
                            $heroProduct = $newProducts->first();
                            $heroImage = \Illuminate\Support\Facades\Storage::disk('public')->exists($heroProduct->image_url)
                                ? url('/storage/'.$heroProduct->image_url)
                                : url('/storage/images/products/placeholder.svg');
                        @endphp
                        <a href="{{ route('products.show', $heroProduct) }}" class="group block overflow-hidden rounded-[1.5rem] bg-slate-100 dark:bg-slate-800">
                            <img src="{{ $heroImage }}" alt="{{ $heroProduct->title }}" width="720" height="600" fetchpriority="high" class="aspect-[1.15] w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                        </a>
                        <div class="flex flex-wrap items-end justify-between gap-4 px-2 pb-2 pt-5 sm:px-3">
                            <div>
                                <x-badge variant="primary">{{ __('public.home.new_heading') }}</x-badge>
                                <h2 class="mt-3 font-display text-2xl font-bold text-ink">{{ $heroProduct->title }}</h2>
                                <p class="mt-1 text-sm text-muted">{{ $heroProduct->seller->bussiness_name ?: $heroProduct->seller->name }}</p>
                            </div>
                            <span class="rounded-xl bg-accent/30 px-3 py-2 text-sm font-extrabold text-slate-950">Rp {{ number_format((int) $heroProduct->price, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <img src="{{ url('/storage/images/products/placeholder.svg') }}" alt="{{ __('public.common.empty_products_title') }}" width="720" height="600" class="aspect-[1.15] w-full rounded-[1.5rem] object-cover">
                        <p class="p-5 font-display text-xl font-bold text-ink">{{ __('public.common.empty_products_title') }}</p>
                    @endif
                </div>
                <div class="absolute -bottom-6 -left-3 rounded-2xl border border-slate-200 bg-surface px-4 py-3 shadow-card sm:-left-10 sm:px-5">
                    <p class="text-xs font-semibold text-muted">{{ __('public.home.categories_heading') }}</p>
                    <p class="mt-1 font-display text-lg font-bold text-primary dark:text-blue-200">{{ $categories->count() }} <span class="text-sm font-semibold text-muted">{{ __('public.nav.catalog') }}</span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="page-container py-16 sm:py-20" data-reveal>
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4 sm:mb-10">
            <div>
                <span class="eyebrow">01 / {{ __('public.home.new_heading') }}</span>
                <h2 class="mt-4 font-display text-3xl font-bold text-ink sm:text-4xl">{{ __('public.home.new_heading') }}</h2>
                <p class="mt-2 text-sm text-muted">{{ __('public.home.new_lead') }}</p>
            </div>
            <x-button :href="route('catalog.index')" variant="secondary">{{ __('public.common.view_all') }}</x-button>
        </div>
        @if ($newProducts->isEmpty())
            <x-empty-state :title="__('public.common.empty_products_title')" :description="__('public.common.empty_products_body')" />
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($newProducts->take(4) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </section>

    <section class="bg-surface py-16 dark:bg-slate-900/40 sm:py-20" data-reveal>
        <div class="page-container">
            <div class="mb-8 flex flex-wrap items-end justify-between gap-4 sm:mb-10">
                <div>
                    <span class="eyebrow">02 / {{ __('public.home.featured_heading') }}</span>
                    <h2 class="mt-4 font-display text-3xl font-bold text-ink sm:text-4xl">{{ __('public.home.featured_heading') }}</h2>
                    <p class="mt-2 text-sm text-muted">{{ __('public.home.featured_lead') }}</p>
                </div>
                <x-button :href="route('catalog.index')" variant="secondary">{{ __('public.common.view_all') }}</x-button>
            </div>
            @if ($featuredProducts->isEmpty())
                <x-empty-state :title="__('public.common.empty_products_title')" :description="__('public.common.empty_products_body')" />
            @else
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($featuredProducts->take(4) as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="page-container py-16 sm:py-20" data-reveal>
        <div class="mx-auto max-w-2xl text-center">
            <span class="eyebrow">{{ __('public.home.categories_heading') }}</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-ink sm:text-4xl">{{ __('public.home.categories_heading') }}</h2>
            <p class="mt-3 text-sm leading-6 text-muted">{{ __('public.home.categories_lead') }}</p>
        </div>
        <div class="mt-9 grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
            @foreach ($categories as $index => $category)
                <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="group flex min-h-32 flex-col justify-between rounded-2xl border border-slate-200 bg-surface p-5 transition hover:-translate-y-1 hover:border-primary/30 hover:shadow-card dark:border-slate-700">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $index % 2 ? 'bg-accent/25 text-slate-900' : 'bg-primary/10 text-primary dark:text-blue-200' }}">
                        <span class="font-display text-lg font-bold">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($category->name, 0, 1)) }}</span>
                    </span>
                    <span class="mt-5 flex items-center justify-between gap-2 font-bold text-ink"><span>{{ $category->name }}</span><span class="text-primary transition group-hover:translate-x-1 dark:text-blue-200" aria-hidden="true">→</span></span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="border-y border-slate-200 bg-page py-16 dark:border-slate-800 sm:py-20" data-reveal>
        <div class="page-container">
            <div class="max-w-2xl">
                <span class="eyebrow">{{ __('public.home.how_eyebrow') }}</span>
                <h2 class="mt-4 font-display text-3xl font-bold text-ink sm:text-4xl">{{ __('public.home.how_heading') }}</h2>
                <p class="mt-3 text-sm leading-7 text-muted">{{ __('public.home.how_lead') }}</p>
            </div>
            <div class="mt-9 grid gap-4 md:grid-cols-3">
                @foreach ([1, 2, 3] as $step)
                    <article class="rounded-3xl border border-slate-200 bg-surface p-6 dark:border-slate-700">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary text-sm font-black text-white dark:text-slate-950">0{{ $step }}</span>
                        <h3 class="mt-5 font-display text-xl font-bold text-ink">{{ __('public.home.step_'.$step.'_title') }}</h3>
                        <p class="mt-2 text-sm leading-6 text-muted">{{ __('public.home.step_'.$step.'_body') }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="page-container py-16 sm:py-20" data-reveal>
        <div class="relative overflow-hidden rounded-[2rem] bg-primary px-6 py-10 text-white shadow-soft sm:px-10 sm:py-12 lg:flex lg:items-center lg:justify-between lg:gap-10 dark:text-slate-950">
            <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-accent/30 blur-3xl" aria-hidden="true"></div>
            <div class="relative max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-white/75 dark:text-slate-900/70">{{ __('public.home.seller_cta') }}</p>
                <h2 class="mt-3 font-display text-3xl font-bold sm:text-4xl">{{ __('public.home.seller_panel_title') }}</h2>
                <p class="mt-3 max-w-xl text-sm leading-7 text-white/80 dark:text-slate-900/80">{{ __('public.home.seller_panel_body') }}</p>
                @if ($isDemoContact)
                    <p class="mt-4 rounded-xl bg-slate-950/15 px-4 py-3 text-xs font-semibold text-white dark:bg-white/40 dark:text-slate-950">{{ __('public.common.demo_warning') }}</p>
                @endif
            </div>
            <div class="relative mt-7 shrink-0 lg:mt-0">
                @if ($adminWhatsappUrl)
                    <x-button :href="$adminWhatsappUrl" target="_blank" rel="noopener" variant="accent">{{ __('public.home.contact_admin') }}</x-button>
                @else
                    <x-button :href="route('about')" variant="accent">{{ __('public.home.seller_cta') }}</x-button>
                @endif
            </div>
        </div>
    </section>
@endsection
