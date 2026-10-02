@props(['title', 'description', 'action' => null, 'href' => null])
<section {{ $attributes->class(['rounded-3xl border border-dashed border-slate-300 bg-surface px-6 py-14 text-center dark:border-slate-700']) }}>
    <span class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary" aria-hidden="true">
        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none"><path d="M4 5.75A1.75 1.75 0 0 1 5.75 4h12.5A1.75 1.75 0 0 1 20 5.75v12.5A1.75 1.75 0 0 1 18.25 20H5.75A1.75 1.75 0 0 1 4 18.25V5.75Z" stroke="currentColor" stroke-width="1.6"/><path d="m7 15 3-3 2 2 2-2 3 3M9 8h.01" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </span>
    <h3 class="font-display text-2xl font-bold text-ink">{{ $title }}</h3>
    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-muted">{{ $description }}</p>
    @if ($action && $href)
        <div class="mt-6"><x-button :href="$href">{{ $action }}</x-button></div>
    @endif
</section>
