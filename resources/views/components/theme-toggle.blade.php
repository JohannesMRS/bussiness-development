@props(['label' => __('public.theme.toggle')])
<button type="button" data-theme-toggle aria-label="{{ $label }}" aria-pressed="false" class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-full border border-slate-200 bg-surface text-ink transition hover:border-primary hover:text-primary dark:border-slate-700" title="{{ $label }}">
    <svg class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
    <svg class="hidden h-5 w-5 dark:block" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.2 15.3A8.5 8.5 0 0 1 8.7 3.8 8.5 8.5 0 1 0 20.2 15.3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
</button>
