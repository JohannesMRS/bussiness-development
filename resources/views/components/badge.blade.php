@props(['variant' => 'default'])
@php
    $variantClasses = match ($variant) {
        'success' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200',
        'warning' => 'bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-200',
        'danger' => 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-200',
        'primary' => 'bg-primary/10 text-primary dark:bg-primary/15 dark:text-blue-200',
        default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
    };
@endphp
<span {{ $attributes->class(['inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold', $variantClasses]) }}>{{ $slot }}</span>
