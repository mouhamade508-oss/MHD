<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? 'MHD print lab - جميع المنتجات' }}</title>
    
@vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --clothing-hero-height: 500px;
        }
        .hero-slide { min-height: var(--clothing-hero-height); background-size: cover; background-position: center; }
        .navbar-brand-img { width: 40px; height: 40px; border-radius: 50%; }
        @media (max-width: 768px) { :root { --clothing-hero-height: 350px; } }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center text-dark" href="{{ route('home') }}">
                <div class="bg-primary text-white d-flex align-items-center justify-content-center navbar-brand-img me-2">
                    MHD
                </div>
                MHD print lab
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#shop">المتجر</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">الفئات</a>
                        <ul class="dropdown-menu">
                            @foreach($categories ?? [] as $category)
                                <li><a class="dropdown-item" href="?category={{ $category->id }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#cart">سلة</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    @auth
                        <span class="me-3">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">خروج</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary me-2">دخول Admin</a>
                    @endauth
                    <button class="btn btn-outline-primary position-relative" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                        <i class="bi bi-funnel"></i> فلاتر
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel - Slide 1 صورة طباعة -->
    <div id="heroCarousel" class="carousel slide carousel-fade shadow-lg" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active hero-slide" style="background-image: url('https://images.unsplash.com/photo-1581235687232-f1bd7bb22c3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                <div class="d-flex align-items-center justify-content-center h-100 bg-dark bg-opacity-40">
                    <div class="text-center text-white">
                        <h1 class="display-4 fw-bold mb-4">طباعة عالية الجودة</h1>
                        <p class="lead mb-4">خدمات طباعة احترافية لكل احتياجاتك</p>
                        <a href="#shop" class="btn btn-light btn-lg">تسوق الآن</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item hero-slide" style="background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                <div class="d-flex align-items-center justify-content-center h-100 bg-primary bg-opacity-70">
                    <div class="text-center text-white">
                        <h1 class="display-4 fw-bold mb-4">حسومات واسعار مناسبه</h1>
                        <p class="lead mb-4">لا تفوت العروض المحدودة</p>
                        <a href="#sale" class="btn btn-warning btn-lg">عروض خاصة</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item hero-slide" style="background-image: url('https://images.unsplash.com/photo-1571903532331-b158dd43a3ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                <div class="d-flex align-items-center justify-content-center h-100 bg-success bg-opacity-40">
                    <div class="text-center text-white">
                        <h1 class="display-4 fw-bold mb-4">منتجات مميزة</h1>
                        <p class="lead mb-4">أفضل الخيارات لك</p>
                        <a href="#featured" class="btn btn-light btn-lg">اكتشف المميز</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- باقي المحتوى نفسه -->
    <!-- Category Pills -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="h3 fw-bold">تسوق حسب الفئة</h2>
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                @foreach($categories ?? [] as $category)
                    <a href="?category={{ $category->id }}" class="btn btn-outline-primary rounded-pill px-4 py-2 {{ request('category') == $category->id ? 'active fw-bold' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
                <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">الكل</a>
            </div>
        </div>
    </section>

    <!-- Offcanvas Filters -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">فلاتر البحث</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form method="GET" class="filter-form">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold">الفئة</label>
                    @foreach($categories ?? [] as $category)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="cat{{ $category->id }}" {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">نطاق السعر</label>
                    <div class="row g-2">
                        <div class="col">
                            <input type="number" class="form-control" name="price_min" placeholder="الحد الأدنى" value="{{ request('price_min') }}">
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" name="price_max" placeholder="الحد الأقصى" value="{{ request('price_max') }}">
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">الترتيب</label>
                    <select class="form-select" name="sort">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>السعر تصاعدي</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>السعر تنازلي</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">تطبيق الفلاتر</button>
            </form>
        </div>
    </div>

    <!-- Featured Products -->
    <section id="featured" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="h2 fw-bold mb-3">المنتجات المميزة</h2>
                <p class="lead text-muted">أفضل اختياراتنا لك</p>
            </div>
            <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row g-4">
                            @foreach($featuredProducts->take(8) ?? [] as $index => $product)
                                @if($index < 4)
                                    <x-product-card :product="$product" />
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row g-4">
                            @foreach($featuredProducts->slice(4, 4) ?? [] as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>

    <!-- Main Products Grid -->
    <section id="shop" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="h3 fw-bold mb-1">{{ $products->count() ?? 0 }} منتج</h2>
                            @if($searchTerm)
                                <small class="text-muted d-block mb-1">نتائج البحث عن: <strong>"{{ $searchTerm }}"</strong></small>
                            @else
                                <small class="text-muted">جميع المنتجات المتاحة</small>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-grid-3x3-gap"></i> عرض
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="?view=grid">شبكة</a></li>
                                    <li><a class="dropdown-item" href="?view=list">قائمة</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    @if($products->isEmpty())
                        <div class="text-center py-8">
                            <i class="bi bi-bag-check display-1 text-muted mb-4"></i>
                            <h3 class="fw-bold text-muted mb-3">لا توجد منتجات</h3>
                            <p class="text-muted mb-4">تحقق من الفلاتر أو أعد المحاولة لاحقاً</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">عرض الكل</a>
                        </div>
                    @else
                        <div class="row g-4" id="products-grid">
                            @foreach($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>
                        
                        {{-- Pagination --}}
{{ $products->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 45px; height: 45px; border-radius: 50%;">
                            MHD
                        </div>
                        <h4 class="mb-0">MHD print lab</h4>
                    </div>
                    <p class="text-light">متجر طباعه بأحدث الصيحات. طلب سهل عبر واتساب.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>روابط سريعة</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-light text-decoration-none">الرئيسية</a></li>
                        <li><a href="#shop" class="text-light text-decoration-none">المتجر</a></li>
                        <li><a href="#categories" class="text-light text-decoration-none">الفئات</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>تواصل معنا</h5>
                    <p class="text-light mb-2">واتساب فوري</p>
                    <a href="https://wa.me/963982617848" class="btn btn-success mb-2 w-100">

                        <i class="bi bi-whatsapp me-2"></i>ابدأ الدردشة
                    </a>
                </div>
                <div class="col-lg-3">
                    <h5>تابعنا</h5>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 opacity-25">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} MHD print lab. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script>
        // Custom clothing store JS
        document.querySelectorAll('.product-hover-overlay').forEach(el => {
            el.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(0,0,0,0.3)';
            });
            el.addEventListener('mouseleave', function() {
                this.style.background = 'rgba(0,0,0,0)';
            });
        });
    </script>
</body>
</html>

