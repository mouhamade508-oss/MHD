<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $product->name }} - MHD print lab</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        .sticky-buy-box { top: 120px; z-index: 1020; }
        .thumb-active { border-color: var(--bs-primary) !important; transform: scale(1.08); box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25) !important; }
        .rating-stars { color: #ffc107; }
        .image-main { transition: transform 0.5s ease; }
        .image-main:hover { transform: scale(1.03); }
        .image-zoom-container { position: relative; }
        .zoom-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); display: none; }
        .nav-pills .nav-link { transition: all 0.3s ease; }
        .nav-pills .nav-link.active { background-color: var(--bs-primary) !important; box-shadow: 0 4px 12px rgba(13,110,253,0.4) !important; transform: translateY(-2px); }
        .card:hover { transform: translateY(-8px); transition: all 0.4s ease; }
        @media (max-width: 992px) { .sticky-buy-box { position: static !important; top: auto !important; } }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <div class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-3 rounded-3 shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="bi bi-house me-1"></i>الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none"><i class="bi bi-shop me-1"></i>المتجر</a></li>
                <li class="breadcrumb-item active fw-bold" aria-current="page">{{ Str::limit($product->name, 30) }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Images Gallery -->
            <div class="col-lg-7">
                <div id="imageCarousel" class="carousel slide shadow-lg" data-bs-ride="false">
                    <div class="carousel-inner">
                        @php
                            $images = $product->image ? [$product->image] : ['images/default-product.jpg'];
                            if (count($images) === 1) $images[] = $images[0];
                        @endphp
                        @foreach($images as $index => $image)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="image-zoom-container position-relative overflow-hidden rounded-4">
                                    <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 image-main" 
                                         style="height: 550px; object-fit: cover;" 
                                         alt="{{ $product->name }}"
                                         data-zoom-src="{{ asset('storage/' . $image) }}">
                                    <div class="zoom-overlay"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-primary bg-opacity-75 rounded-circle p-2"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-primary bg-opacity-75 rounded-circle p-2"></span>
                    </button>
                </div>

                <!-- Thumbnails -->
                <div class="row g-2 mt-4 px-1">
                    @foreach(array_unique($images) as $index => $image)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded-3 thumb-img {{ $index == 0 ? 'thumb-active' : '' }}" 
                                 data-bs-target="#imageCarousel" data-bs-slide-to="{{ $index }}" 
                                 style="cursor: pointer; height: 85px; object-fit: cover; border-radius: 12px; border: 3px solid transparent; transition: all 0.4s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"
                                 onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                    @endforeach
                </div>

                @if($product->video)
                    <div class="mt-4 p-4 border rounded bg-light">
                        <video controls class="w-100 rounded" style="height: 250px;">
                            <source src="{{ asset('storage/' . $product->video) }}" type="video/mp4">
                        </video>
                    </div>
                @endif
            </div>

            <!-- Buy Box -->
            <div class="col-lg-5">
                <div class="card sticky-top sticky-buy-box" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h1 class="h3 fw-bold mb-1">{{ $product->name }}</h1>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rating-stars me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : ($i == ceil($avgRating) ? '-half' : '') }} text-warning"></i>
                                        @endfor
                                    </div>
                                    <span class="text-muted">{{ number_format($avgRating, 1) }} ({{ $reviewCount }} تقييم)</span>
                                </div>
                                <h2 class="display-6 fw-bold text-primary mb-0">
                                    @php
                                        $discount = session('product_discount_' . $product->id);
                                        $displayPrice = $discount ? $discount['new_price'] : $product->price;
                                        $hasDiscount = $discount ? true : false;
                                    @endphp
                                    @if($hasDiscount)
                                        <s class="text-muted me-2">ل.س {{ number_format($product->price, 0) }}</s>
                                        <span class="text-success">ل.س {{ number_format($displayPrice, 0) }}</span>
                                        <span class="badge bg-success ms-2">خصم {{ $discount['percentage'] }}%</span>
                                    @else
                                        ل.س {{ number_format($displayPrice, 0) }}
                                    @endif
                                </h2>

                                <!-- Discount Code -->
                                @if(!$hasDiscount)
                                    <div class="mb-4">
                                        <form method="POST" action="{{ route('product.discount.apply', $product) }}" class="needs-validation" novalidate>
                                            @csrf
                                            <label class="form-label fw-bold">كود الخصم</label>
                                            <div class="input-group">
                                                <input type="text" name="discount_code" class="form-control @error('discount_code') is-invalid @enderror" placeholder="أدخل كود الخصم (مثال: SALE20)" required>
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="bi bi-tag-fill"></i> تطبيق
                                                </button>
                                            </div>
                                            @error('discount_code')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </form>
                                    </div>
                                @else
                                    <div class="alert alert-success">
                                        <i class="bi bi-check-circle-fill"></i> تم تطبيق كود الخصم <strong>{{ $discount['code'] }}</strong> 
                                        بنجاح (<span class="badge bg-success">{{ $discount['percentage'] }}%</span>)
                                        @if(session('success'))
                                            <div class="mt-2">{{ session('success') }}</div>
                                        @endif
                                    </div>
                                    <a href="{{ route('product.show', $product) }}?_clear_discount=1" class="btn btn-sm btn-outline-danger mt-2" onclick="return confirm('هل أنت متأكد من إلغاء الخصم؟')">
                                        <i class="bi bi-x-circle"></i> إلغاء الخصم
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Dynamic Variant Selectors -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2 d-block">اللون:</label>
                            <div class="d-flex gap-2 flex-wrap" id="colorSwatches">
@php $colors = is_array($product->available_colors) ? $product->available_colors : json_decode($product->available_colors, true) ?? []; @endphp
@if($colors && count($colors) > 0)
                                    @foreach($colors as $index => $color)
                                        <button type="button" class="color-swatch border rounded-circle {{ ($selectedColor == $color) ? 'active' : ($index == 0 ? 'active' : '') }}" 
                                                style="width: 40px; height: 40px; background: {{ $color }};" data-color="{{ $color }}"></button>
                                    @endforeach
                                @else
                                    <button type="button" class="color-swatch border rounded-circle active" style="width: 40px; height: 40px; background: #333;" data-color="black"></button>
                                    <small class="text-muted mt-1">لا توجد ألوان محددة</small>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2">المقاس:</label>
                            <div class="btn-group w-100 flex-wrap" role="group" id="sizeButtons">
@php $sizes = is_array($product->sizes_available) ? $product->sizes_available : json_decode($product->sizes_available, true) ?? []; @endphp
@if($sizes && count($sizes) > 0)
                                    @foreach($sizes as $index => $size)
                                        <button type="button" class="btn btn-outline-secondary {{ ($selectedSize == $size) ? 'active' : ($index == 0 ? 'active' : '') }}" data-size="{{ $size }}">{{ $size }}</button>
                                    @endforeach
                                @else
                                    <button type="button" class="btn btn-outline-secondary active" data-size="M">M</button>
                                    <small class="text-muted mt-1 d-block">لا توجد مقاسات محددة</small>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <a href="{{ route('product.whatsapp', $product) }}" target="_blank" class="btn btn-success w-100 py-3 fs-5 fw-bold">
                                    <i class="bi bi-whatsapp me-2"></i> اطلب عبر واتساب
                                </a>
                            </div>
                            <div class="col">
                                <button class="btn btn-outline-primary w-100 py-3 fs-5" onclick="addToCart({{ $product->id }})">
                                    <i class="bi bi-cart-plus me-2"></i> أضف للسلة
                                </button>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="mb-4">
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> متوفر: {{ $stock ?? $product->variant_stock ?? 10 }} قطعة لكل variant</span>
                            @if(isset($selectedColor) || isset($selectedSize))
                                <span class="badge bg-info ms-2">مختار: {{ $selectedColor ?? 'أي' }} / {{ $selectedSize ?? 'أي' }}</span>
                            @endif
                        </div>

                        <!-- Features -->
                        <div class="mb-4">
                            <ul class="list-unstyled text-muted small">
                                <li class="d-flex align-items-center mb-2"><i class="bi bi-check text-success me-2"></i>منتجات عاليه الجوده</li>
                                <li class="d-flex align-items-center mb-2"><i class="bi bi-check text-success me-2"></i>تصميم عصري</li>
                                <li class="d-flex align-items-center mb-2"><i class="bi bi-check text-success me-2"></i>شحن سريع</li>
                                <li class="d-flex align-items-center"><i class="bi bi-check text-success me-2"></i>ضمان الجودة</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Form -->
            <div class="col-lg-12 mt-4">
                <x-review-form :product="$product" :hasReviewed="$hasReviewed ?? false" />
            </div>
        </div>

        <!-- Tabs (Description, Specs, Reviews) -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs nav-fill nav-pills shadow-lg px-4 py-2 rounded-pill mb-5 bg-white" id="productTabs" role="tablist">
                    <li class="nav-item flex-fill mx-1">
                        <button class="nav-link rounded-pill w-100 py-3 px-4 fs-5 fw-semibold active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button">
                            <i class="bi bi-file-text me-2"></i>الوصف
                        </button>
                    </li>
                    <li class="nav-item flex-fill mx-1">
                        <button class="nav-link rounded-pill w-100 py-3 px-4 fs-5 fw-semibold" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button">
                            <i class="bi bi-gear me-2"></i>المواصفات
                        </button>
                    </li>
                    <li class="nav-item flex-fill mx-1">
                        <button class="nav-link rounded-pill w-100 py-3 px-4 fs-5 fw-semibold{{ isset($reviewCount) && $reviewCount > 0 ? ' active' : '' }}" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                            <i class="bi bi-star me-2"></i>التقييمات <span class="badge bg-primary ms-1">{{ $reviewCount ?? 0 }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabsContent">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <small class="d-block mt-1 text-muted">التقييم قيد المراجعة وسيظهر قريباً بعد الموافقة.</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="tab-pane fade{{ isset($reviewCount) && $reviewCount > 0 ? '' : ' show active' }}" id="desc" role="tabpanel">
                        <div class="card shadow-sm">
                            <div class="card-body p-5">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="specs" role="tabpanel">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6>المواد</h6>
                                        <p class="text-muted mb-0">{{ $product->materials ?: 'غير محدد' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>الصيانة</h6>
                                        <p class="text-muted mb-0">{{ $product->care_instructions ?: 'غير محدد' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>المنشأ</h6>
                                        <p class="text-muted mb-0">{{ $product->origin ?: 'غير محدد' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>الضمان</h6>
                                        <p class="text-muted mb-0">{{ $product->warranty ?: 'غير محدد' }}</p>
                                    </div>
@if($colors && count($colors) > 0)
                                    <div class="col-md-6">
                                        <h6>الألوان المتاحة</h6>
                                        <div class="d-flex gap-2 flex-wrap">
                                            @foreach($colors as $color)
                                                <span class="badge rounded-circle" style="width: 30px; height: 30px; background: {{ $color }}; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
@if($sizes && count($sizes) > 0)
                                    <div class="col-md-6">
                                        <h6>المقاسات المتاحة</h6>
                                        <div class="d-flex gap-1 flex-wrap">
                                            @foreach($sizes as $size)
                                                <span class="badge bg-light text-dark border px-2 py-1">{{ $size }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="text-center mb-5 p-4 bg-light rounded">
                            <h2 class="fw-bold text-primary mb-2">{{ number_format($avgRating ?? 0, 1) }}/5 
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= ($avgRating ?? 0) ? '-fill' : '' }} text-warning fs-3"></i>
                                @endfor
                            </h2>
                            <p class="mb-0">({{ $reviewCount ?? 0 }} تقييم)</p>
                        </div>

                        @forelse($reviews ?? [] as $review)
                            <div class="review-item card shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0">{{ $review->user->name ?? 'زائر' }}</h6>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-muted">{{ $review->comment }}</p>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-star display-1 text-muted opacity-50 mb-3"></i>
                                <h5>لا توجد تقييمات بعد</h5>
                                <p class="text-muted">كن أول من يقيم هذا المنتج!</p>
                            </div>
                        @endforelse
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <section class="mt-5">
            <div class="container">
                <h2 class="h2 fw-bold text-center mb-5 text-primary position-relative">
                    <i class="bi bi-grid-3x3-gap display-4 d-block mb-3 text-opacity-75"></i>
                    منتجات مشابهة قد تعجبك
                    <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-success px-4 py-2 shadow-lg">جديد</span>
                </h2>
                <div class="row g-4">
                    @php $relatedMock = [
                        ['name' => 'T-Shirt Classic', 'price' => 25000, 'img' => 'images/tshirt1.jpg'],
                        ['name' => 'Hoodie Winter', 'price' => 45000, 'img' => 'images/hoodie.jpg'],
                        ['name' => 'Jeans Slim Fit', 'price' => 35000, 'img' => 'images/jeans.jpg'],
                        ['name' => 'Sneakers Sport', 'price' => 18000, 'img' => 'images/sneakers.jpg']
                    ]; @endphp
                    @foreach($relatedMock as $rel)
                        <div class="col-lg-3 col-md-6">
                            <div class="card h-100 border-0 shadow-lg hover-shadow-lg rounded-4 overflow-hidden position-relative transition-all">
                                <div class="position-relative overflow-hidden">
                                    <img src="{{ asset('storage/' . ($rel['img'] ?? 'images/default-product.jpg')) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $rel['name'] }}">
                                    <div class="position-absolute top-3 end-3">
                                        <span class="badge bg-danger rounded-pill px-3 py-2 shadow">شائع</span>
                                    </div>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h6 class="card-title fw-bold mb-2 lh-1">{{ $rel['name'] }}</h6>
                                    <p class="text-muted small flex-grow-1 mb-3">منتج عالي الجودة بأحدث التصاميم</p>
                                    <div class="h5 fw-bold text-primary mb-3">ل.س {{ number_format($rel['price'], 0) }}</div>
                                    <a href="#" class="btn btn-primary w-100 rounded-3 shadow-sm">عرض التفاصيل</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4"><h4 class="mb-3">MHD print lab</h4><p class="text-light">منتجات عصريه وحديثه</p></div>
                <div class="col-lg-2 col-md-6"><h5>روابط</h5><ul class="list-unstyled"><li><a href="#" class="text-light text-decoration-none">المتجر</a></li></ul></div>
                <div class="col-lg-3 col-md-6"><h5>واتساب</h5><a href="{{ route('product.whatsapp', $product) }}" class="btn btn-success w-100">دردشة الآن</a></div>
                <div class="col-lg-3"><h5>تابعنا</h5><div class="d-flex gap-2"><a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-instagram"></i></a></div></div>
            </div>
            <hr>
            <p class="text-center text-light">&copy; {{ date('Y') }} MHD. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <!-- JS -->
    <script>
    // Gallery thumbs
    document.querySelectorAll('.thumb-img').forEach(thumb => {
        thumb.addEventListener('click', function() {
            document.querySelectorAll('.thumb-img').forEach(t => t.classList.remove('thumb-active'));
            this.classList.add('thumb-active');
        });
    });

        // Dynamic Variant selectors with persistence
        document.querySelectorAll('#colorSwatches .color-swatch').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('#colorSwatches').querySelectorAll('.active').forEach(active => active.classList.remove('active'));
                this.classList.add('active');
                const color = this.dataset.color;
                // Persist via URL
                const url = new URL(window.location);
                url.searchParams.set('color', color);
                window.history.replaceState({}, '', url);
            });
        });

        document.querySelectorAll('#sizeButtons [data-size]').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('#sizeButtons').querySelectorAll('.active').forEach(active => active.classList.remove('active'));
                this.classList.add('active');
                const size = this.dataset.size;
                const url = new URL(window.location);
                url.searchParams.set('size', size);
                window.history.replaceState({}, '', url);
            });
        });

        // Show selected info & stock
        if (typeof selectedColor !== 'undefined' || typeof selectedSize !== 'undefined') {
            const info = document.createElement('div');
            info.className = 'alert alert-info mt-3';
            info.innerHTML = `<i class="bi bi-check-circle"></i> مختار: ${selectedColor || 'أي لون'} - ${selectedSize || 'أي مقاس'} | متوفر: ${stock} قطعة`;
            document.querySelector('.mb-4').appendChild(info);
        }

    // Placeholder function
    function addToCart(productId) {
        alert('تم إضافة المنتج للسلة! (محاكاة)');
    }
    </script>
</body>
</html>