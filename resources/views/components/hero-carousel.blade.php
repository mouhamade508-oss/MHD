<div id="{{ $id ?? 'heroCarousel' }}" class="carousel slide carousel-fade {{ $class ?? 'shadow-lg' }}" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach($slides ?? [1,2,3] as $index => $slide)
            <button type="button" data-bs-target="#{{ $id ?? 'heroCarousel' }}" data-bs-slide-to="{{ $index }}" {{ $index == 0 ? 'class=active aria-current=true' : '' }} aria-label="Slide {{ $index +1 }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner {{ $innerClass ?? '' }}">
        @foreach($slides as $index => $slide)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }} hero-slide" style="background-image: url('{{ $slide['image'] ?? 'https://via.placeholder.com/1920x600?text=Clothing+Slide+' . ($index+1) }}');">
                <div class="d-flex align-items-center justify-content-center h-100 {{ $slide['overlay'] ?? 'bg-dark bg-opacity-40' }}">
                    <div class="text-center text-white px-4">
                        <h1 class="display-{{ $slide['size'] ?? 4 }} fw-bold mb-{{ $slide['mb'] ?? 4 }}">{{ $slide['title'] ?? 'عنوان الشريحة' }}</h1>
                        <p class="lead mb-{{ $slide['mb'] ?? 4 }}">{{ $slide['subtitle'] ?? 'وصف الشريحة' }}</p>
                        @if($slide['cta'] ?? true)
                            <a href="{{ $slide['link'] ?? '#' }}" class="btn btn-light btn-lg px-5">{{ $slide['button'] ?? 'تسوق الآن' }}</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $id ?? 'heroCarousel' }}" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">السابق</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#{{ $id ?? 'heroCarousel' }}" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">التالي</span>
    </button>
</div>
