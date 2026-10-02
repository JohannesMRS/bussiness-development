@extends('layouts.public')

@section('title', __('public.seller.directory_title').' | BizDev HMPS MI Polmed')
@section('meta_description', __('public.seller.directory_lead'))

@section('content')
    <section class="bg-hero-glow">
        <div class="page-container py-14 sm:py-20">
            <span class="eyebrow">{{ __('public.seller.directory_eyebrow') }}</span>
            <h1 class="mt-4 font-display text-4xl font-bold tracking-tight text-ink sm:text-5xl">{{ __('public.seller.directory_title') }}</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-muted sm:text-base">{{ __('public.seller.directory_lead') }}</p>
        </div>
    </section>
    <section class="page-container pb-16 sm:pb-20">
        @if ($sellers->isEmpty())
            <x-empty-state :title="__('public.seller.empty_title')" :description="__('public.seller.empty_body')" />
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($sellers as $seller)
                    <x-seller-card :seller="$seller" />
                @endforeach
            </div>
            <div class="mt-10">{{ $sellers->links() }}</div>
        @endif
    </section>
@endsection
