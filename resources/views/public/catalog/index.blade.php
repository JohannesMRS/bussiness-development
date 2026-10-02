@extends('layouts.public')

@section('title', __('public.catalog.title').' | Bussiness Development')
@section('meta_description', __('public.catalog.lead'))

@section('content')
    <section class="bg-hero-glow">
        <div class="page-container pb-10 pt-14 sm:pb-14 sm:pt-20">
            <span class="eyebrow">{{ __('public.catalog.eyebrow') }}</span>
            <h1 class="mt-4 font-display text-4xl font-bold tracking-tight text-ink sm:text-5xl">{{ __('public.catalog.title') }}</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-muted sm:text-base">{{ __('public.catalog.lead') }}</p>
        </div>
    </section>

    <section class="page-container pb-16 sm:pb-20">
        <form method="GET" action="{{ route('catalog.index') }}" class="-mt-3 rounded-3xl border border-slate-200 bg-surface p-4 shadow-soft sm:p-6 dark:border-slate-700">
            <div class="grid gap-4 lg:grid-cols-[1.4fr_1fr_1fr_auto] lg:items-end">
                <div>
                    <label for="q" class="mb-2 block text-sm font-bold text-ink">{{ __('public.catalog.search_label') }}</label>
                    <input id="q" name="q" type="search" value="{{ $search }}" placeholder="{{ __('public.catalog.search_placeholder') }}" class="form-control">
                </div>
                <div>
                    <label for="category" class="mb-2 block text-sm font-bold text-ink">{{ __('public.catalog.category_label') }}</label>
                    <select id="category" name="category" class="form-control">
                        <option value="">{{ __('public.catalog.all_categories') }}</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->slug }}" @selected($categorySlug === $item->slug)>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="major" class="mb-2 block text-sm font-bold text-ink">{{ __('public.catalog.major_label') }}</label>
                    <select id="major" name="major" class="form-control">
                        <option value="">{{ __('public.catalog.all_majors') }}</option>
                        @foreach ($majors as $item)
                            <option value="{{ $item }}" @selected($major === $item)>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-wrap gap-2 lg:flex-nowrap">
                    <x-button type="submit" class="w-full px-5 lg:w-auto">{{ __('public.catalog.filter') }}</x-button>
                    @if ($search !== '' || $categorySlug !== '' || $major !== '')
                        <x-button :href="route('catalog.index')" variant="secondary" class="w-full px-5 lg:w-auto">{{ __('public.catalog.clear') }}</x-button>
                    @endif
                </div>
            </div>
        </form>

        <div class="mb-6 mt-10 flex items-end justify-between gap-4">
            <div>
                <h2 class="font-display text-2xl font-bold text-ink">{{ __('public.catalog.results') }}</h2>
                <p class="mt-1 text-sm text-muted">{{ trans_choice('public.seller.products_count', $products->total(), ['count' => $products->total()]) }}</p>
            </div>
        </div>

        @if ($products->isEmpty())
            <x-empty-state :title="__('public.common.no_results_title')" :description="__('public.common.no_results_body')" :action="__('public.catalog.clear')" :href="route('catalog.index')" />
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
