@extends('layouts.public')

@section('title', '404 | BizDev HMPS MI Polmed')
@section('meta_description', __('public.error.not_found_body'))

@section('content')
    <section class="page-container flex min-h-[65vh] flex-col items-center justify-center py-16 text-center">
        <span class="font-display text-8xl font-black tracking-tighter text-primary dark:text-blue-200">404</span>
        <h1 class="mt-4 font-display text-3xl font-bold text-ink">{{ __('public.error.not_found_title') }}</h1>
        <p class="mt-3 max-w-md text-sm leading-6 text-muted">{{ __('public.error.not_found_body') }}</p>
        <x-button :href="route('home')" class="mt-7">{{ __('public.error.back_home') }}</x-button>
    </section>
@endsection
