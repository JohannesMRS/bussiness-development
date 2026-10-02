@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex min-h-11 items-center rounded-full bg-primary/5 px-4 text-sm font-semibold text-primary dark:text-blue-200'
            : 'inline-flex min-h-11 items-center rounded-full px-4 text-sm font-semibold text-muted transition hover:bg-primary/5 hover:text-primary focus-visible:ring-secondary dark:hover:text-blue-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
