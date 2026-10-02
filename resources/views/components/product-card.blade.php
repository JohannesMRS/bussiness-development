@props(['product'])
@php
    $productImageUrl = \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image_url)
        ? url('/storage/'.$product->image_url)
        : url('/storage/images/products/placeholder.svg');
@endphp
<article {{ $attributes->class(['group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-surface shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-soft dark:border-slate-700']) }}>
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-[4/3] overflow-hidden bg-slate-100 dark:bg-slate-800">
        <img src="{{ $productImageUrl }}" alt="{{ $product->title }}" width="640" height="480" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        <span class="absolute left-4 top-4"><x-badge variant="primary">{{ $product->category->name }}</x-badge></span>
    </a>
    <div class="flex flex-1 flex-col p-5 sm:p-6">
        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-muted">{{ __('public.product.seller') }}</p>
        <p class="mt-1 truncate text-sm font-semibold text-primary dark:text-blue-200">{{ $product->seller->bussiness_name ?: $product->seller->name }}</p>
        <h3 class="mt-3 font-display text-xl font-bold leading-snug text-ink">
            <a href="{{ route('products.show', $product) }}" class="rounded-sm hover:text-secondary">{{ $product->title }}</a>
        </h3>
        <p class="mt-2 line-clamp-2 min-h-12 text-sm leading-6 text-muted">{{ $product->short_description }}</p>
        <div class="mt-5 flex items-end justify-between gap-3">
            <div>
                <p class="text-xs font-medium text-muted">{{ __('public.product.price') }}</p>
                <p class="mt-1 inline-flex rounded-lg bg-accent/25 px-2.5 py-1 font-bold text-slate-950">Rp {{ number_format((int) $product->price, 0, ',', '.') }}</p>
            </div>
            @if ($product->whatsapp_url)
                <x-button :href="$product->whatsapp_url" target="_blank" rel="noopener" variant="secondary" class="shrink-0 px-4" :aria-label="__('public.product.order_for', ['name' => $product->title])">
                    <span>{{ __('public.product.order') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </x-button>
            @endif
        </div>
    </div>
</article>
