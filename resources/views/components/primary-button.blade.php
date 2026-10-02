<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-11 items-center justify-center rounded-full border border-transparent bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-primary/90 focus-visible:ring-secondary dark:text-slate-950']) }}>
    {{ $slot }}
</button>
