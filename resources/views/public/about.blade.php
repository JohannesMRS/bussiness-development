@extends('layouts.public')

@section('title', __('public.nav.about').' | Bussiness Development')
@section('meta_description', __('public.about.lead'))

@section('content')
    <section class="relative overflow-hidden bg-primary text-white dark:text-slate-950">
        <div class="absolute -right-20 -top-24 h-80 w-80 rounded-full bg-accent/35 blur-3xl" aria-hidden="true"></div>
        <div class="page-container relative py-16 sm:py-24">
            <span class="inline-flex rounded-full border border-white/25 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-white dark:border-slate-900/20 dark:bg-white/30 dark:text-slate-950">{{ __('public.about.eyebrow') }}</span>
            <h1 class="mt-5 max-w-4xl font-display text-4xl font-bold leading-tight sm:text-6xl">{{ __('public.about.title') }}</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-white/80 dark:text-slate-900/80">{{ __('public.about.lead') }}</p>
        </div>
    </section>

    <section class="page-container grid gap-8 py-14 sm:py-20 lg:grid-cols-[1fr_1fr] lg:items-center">
        <div class="overflow-hidden rounded-[2rem] border border-slate-200 shadow-soft dark:border-slate-700" data-reveal>
            <img src="{{ url('/images/about/team-placeholder.svg') }}" alt="{{ __('public.about.team_alt') }}" width="1200" height="760" loading="lazy" class="aspect-[1.5] w-full object-cover">
        </div>
        <div data-reveal>
            <span class="eyebrow">{{ __('public.about.team_title') }}</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-ink">{{ __('public.about.team_title') }}</h2>
            <p class="mt-3 text-sm leading-7 text-muted">{{ __('public.about.team_note') }}</p>
            <div class="mt-7 grid gap-4 sm:grid-cols-2">
                <article class="rounded-2xl border border-slate-200 bg-surface p-5 dark:border-slate-700">
                    <span class="text-xs font-bold uppercase tracking-wider text-primary dark:text-blue-200">{{ __('public.about.vision_title') }}</span>
                    <p class="mt-2 text-sm leading-6 text-muted">{{ __('public.about.vision_demo') }}</p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-surface p-5 dark:border-slate-700">
                    <span class="text-xs font-bold uppercase tracking-wider text-primary dark:text-blue-200">{{ __('public.about.mission_title') }}</span>
                    <p class="mt-2 text-sm leading-6 text-muted">{{ __('public.about.mission_demo') }}</p>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-surface py-14 dark:bg-slate-900/40 sm:py-20" data-reveal>
        <div class="page-container">
            <div class="max-w-2xl">
                <span class="eyebrow">{{ __('public.about.workflow_title') }}</span>
                <h2 class="mt-4 font-display text-3xl font-bold text-ink sm:text-4xl">{{ __('public.about.workflow_title') }}</h2>
            </div>
            <ol class="mt-8 grid gap-4 md:grid-cols-3">
                @foreach ([1, 2, 3] as $step)
                    <li class="rounded-3xl border border-slate-200 bg-page p-6 dark:border-slate-700">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-accent/30 font-black text-slate-950">0{{ $step }}</span>
                        <p class="mt-4 text-sm leading-7 text-muted">{{ __('public.about.workflow_'.$step) }}</p>
                    </li>
                @endforeach
            </ol>
            <p class="mt-6 rounded-2xl border border-primary/15 bg-primary/5 p-5 text-sm font-medium leading-7 text-ink">{{ __('public.about.fee_note') }}</p>
        </div>
    </section>

    <section class="page-container py-14 sm:py-20" data-reveal>
        <div class="flex flex-col gap-6 rounded-[2rem] border border-slate-200 bg-surface p-6 shadow-card sm:p-9 lg:flex-row lg:items-center lg:justify-between dark:border-slate-700">
            <div>
                <h2 class="font-display text-2xl font-bold text-ink sm:text-3xl">{{ __('public.about.contact_title') }}</h2>
                <p class="mt-2 max-w-2xl text-sm leading-7 text-muted">{{ __('public.about.contact_body') }}</p>
                @if ($isDemoContact)
                    <p class="mt-3 text-xs font-semibold text-amber-700 dark:text-amber-300">{{ __('public.common.demo_warning') }}</p>
                @endif
            </div>
            @if ($adminWhatsappUrl)
                <x-button :href="$adminWhatsappUrl" target="_blank" rel="noopener" variant="accent" class="shrink-0">{{ __('public.about.contact_button') }}</x-button>
            @endif
        </div>
    </section>
@endsection
