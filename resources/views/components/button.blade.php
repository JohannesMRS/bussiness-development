@props(['href' => null, 'variant' => 'primary', 'type' => 'button', 'target' => null, 'rel' => null])
@php
    $variantClasses = match ($variant) {
        'secondary' => 'border border-primary/20 bg-surface text-primary hover:border-primary hover:bg-primary/5 dark:border-slate-600 dark:text-blue-200 dark:hover:bg-slate-800',
        'accent' => 'bg-accent text-slate-950 hover:brightness-95',
        'quiet' => 'bg-transparent text-ink hover:bg-slate-100 dark:hover:bg-slate-800',
        default => 'bg-primary text-white hover:bg-primary/90 dark:text-slate-950',
    };
@endphp
@if ($href)
    <a href="{{ $href }}" @if ($target) target="{{ $target }}" @endif @if ($rel) rel="{{ $rel }}" @endif {{ $attributes->class(['inline-flex min-h-11 items-center justify-center gap-2 rounded-full px-5 py-2.5 text-sm font-bold shadow-sm transition duration-200 focus-visible:ring-2 focus-visible:ring-secondary', $variantClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class(['inline-flex min-h-11 items-center justify-center gap-2 rounded-full px-5 py-2.5 text-sm font-bold shadow-sm transition duration-200 focus-visible:ring-2 focus-visible:ring-secondary', $variantClasses]) }}>
        {{ $slot }}
    </button>
@endif
