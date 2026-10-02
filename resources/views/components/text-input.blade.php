@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'form-control disabled:cursor-not-allowed disabled:opacity-60']) }}>
