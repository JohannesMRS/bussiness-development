@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block min-h-11 w-full rounded-xl bg-primary/5 px-3 py-3 text-start text-sm font-semibold text-primary dark:text-blue-200'
            : 'block min-h-11 w-full rounded-xl px-3 py-3 text-start text-sm font-semibold text-muted transition hover:bg-primary/5 hover:text-primary focus-visible:ring-secondary dark:hover:text-blue-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
