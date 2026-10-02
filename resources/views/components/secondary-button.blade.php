<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex min-h-11 items-center justify-center rounded-full border border-slate-300 bg-surface px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:border-primary/40 hover:bg-primary/5 disabled:opacity-50 dark:border-slate-600']) }}>
    {{ $slot }}
</button>
