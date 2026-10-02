@props(['status'])

@if ($status)
    <div role="status" {{ $attributes->merge(['class' => 'rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200']) }}>
        {{ $status }}
    </div>
@endif
