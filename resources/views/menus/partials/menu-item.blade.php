<div class="menu-item">
    <!-- <div class="item-img-wrap">
        @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
        @else
            <div class="item-img-placeholder">
                <i class="bi bi-image"></i>
                <span>Photo</span>
            </div>
        @endif
    </div> -->
    <div class="item-body">
        <h5 class="item-name">{{ $item->name }}</h5>
        <p class="item-desc">{{ $item->description }}</p>
        @if($item->dietary_info)
            <div class="mt-1">
                @foreach($item->dietary_info as $info)
                    <span class="badge bg-light text-muted border fw-normal me-1" style="font-size: 0.65rem;">{{ strtoupper($info) }}</span>
                @endforeach
            </div>
        @endif
    </div>
    <div class="item-price">{{ $restaurant->settings['currency'] ?? 'NPR' }} {{ number_format($item->price, 2) }}</div>
</div>
