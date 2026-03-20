<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold fs-4 d-flex align-items-center text-dark text-decoration-none" href="{{ route('home') }}">
            <div class="bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 45px; height: 45px; border-radius: 50%; font-weight: bold;">
                MHD
            </div>
MHD print lab
        </a>
        
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Collapsible -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Main Menu -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('home') }}#shop">المتجر</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        الفئات
                    </a>
                    <ul class="dropdown-menu">
                        @foreach(\App\Models\Category::take(6)->get() as $category)
                            <li><a class="dropdown-item" href="?category={{ $category->id }}">{{ $category->name }}</a></li>
                        @endforeach
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center fw-bold" href="{{ route('home') }}#categories">عرض الكل</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#cart"><i class="bi bi-cart"></i> سلة المشتريات <span class="badge bg-primary ms-1 cart-count" style="display: none;">0</span></a>
                </li>
            </ul>
            
            <!-- Right side: Search, Cart, Auth -->
            <div class="d-flex align-items-center gap-2">
                <!-- Quick Search -->
                <form action="{{ route('products.search') }}" method="GET" class="d-flex me-3">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control border-end-0" name="q" placeholder="ابحث عن ملابس...">
                        <span class="input-group-text bg-white border-start-0">
                            <i class="bi bi-search"></i>
                        </span>
                    </div>
                </form>
                
                <!-- Cart Icon -->
                <button class="btn btn-outline-primary position-relative me-2" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                    <i class="bi bi-bag"></i>
                    <span class="badge bg-danger cart-badge">3</span>
                </button>
                
                <!-- Auth -->
                @auth
                    <div class="dropdown">
                        <a class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&size=32' }}" class="rounded-circle me-1" width="32" height="32" alt="{{ auth()->user()->name }}">
                            {{ Str::limit(auth()->user()->name, 15) }}
                            @if(auth()->user()->is_admin)
                                <span class="badge bg-warning ms-1">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">الملف الشخصي</a></li>
@if(auth()->user()->is_admin)
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">المنتجات</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">الفئات</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.reviews.index') }}">التقييمات</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit">خروج</button>
                            </form>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm me-1">دخول</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">إنشاء حساب</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Cart Offcanvas (simple) -->
<div class="offcanvas offcanvas-end" id="cartOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">سلة المشتريات</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <p class="text-center py-5 text-muted">سلة المشتريات فارغة</p>
        <div class="d-grid">
            <a href="#shop" class="btn btn-primary">ابدأ التسوق</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple cart count update
    document.querySelectorAll('.cart-count, .cart-badge').forEach(el => {
        el.textContent = '3'; // Mock
        el.style.display = 'inline';
    });
</script>
@endpush>
