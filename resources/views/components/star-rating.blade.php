@props(['rating' => 0])

@php
    $rating = $rating ?? 0;
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
@endphp

<div {{ $attributes->merge(['class' => 'stars text-warning']) }}>
    @for ($i = 0; $i < $fullStars; $i++)
        <i class="fa-solid fa-star"></i>
    @endfor
    
    @if ($hasHalfStar)
        <i class="fa-solid fa-star-half-stroke"></i>
    @endif
    
    @for ($i = 0; $i < $emptyStars; $i++)
        <i class="fa-regular fa-star"></i>
    @endfor
</div>