@php
    $productImageUrl = \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image_url)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_url)
        : asset('storage/images/products/placeholder.svg');
@endphp
<article>
    <a href="{{ route('products.show', $product) }}">
        <img src="{{ $productImageUrl }}" alt="{{ $product->title }}" width="640" height="480" loading="lazy">
        <h3>{{ $product->title }}</h3>
    </a>
    <p>Usaha: {{ $product->seller->bussiness_name ?: $product->seller->name }}</p>
    <p>Kategori: {{ $product->category->name }}</p>
    <p>Rp {{ number_format((int) $product->price, 0, ',', '.') }}</p>
    <p>{{ $product->short_description }}</p>
    @if ($product->whatsapp_url)
        <a href="{{ $product->whatsapp_url }}" target="_blank" rel="noopener">Pesan via WhatsApp</a>
    @endif
</article>
