<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة المنتجات - MHD Print Lab Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --mhd-primary: #1F3C88;
            --mhd-primary-light: #3B5BBE;
            --mhd-dark: #1a1a2e;
        }
        .btn-primary { background: var(--mhd-primary); border-color: var(--mhd-primary); }
        .btn-primary:hover { background: var(--mhd-primary-light) !important; border-color: var(--mhd-primary-light); }
        .navbar-brand-logo { background: var(--mhd-primary); color: white; border-radius: 8px; font-weight: 700; padding: 0.5rem 1rem; }
        .table-card { box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden; }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .stats-card { background: linear-gradient(135deg, var(--mhd-primary), var(--mhd-primary-light)); color: white; }
        .empty-state { min-height: 400px; }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .table-responsive { font-size: 0.875rem; }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center me-3" href="{{ route('admin.products.index') }}">
                <span class="navbar-brand-logo me-2 fs-5">MHD</span>
                <span>إدارة المنتجات</span>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.products.index') }}">المنتجات</a>
<a class="nav-link" href="{{ route('admin.categories.index') }}">الفئات</a>
                <a class="nav-link" href="{{ route('admin.discounts.index') }}">أكواد الخصم</a>
                <a class="nav-link" href="{{ route('admin.products.create') }}">إضافة جديد</a>
                <span class="navbar-text me-2">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">خروج</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Flash Messages -->
@if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg border-start border-success border-5" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-1 fw-bold text-dark">إدارة المنتجات</h1>
                <small class="text-muted">عرض وإدارة جميع المنتجات</small>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg me-2"></i>إضافة منتج جديد
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4 stats-grid">
            <div class="col-md-3">
                <div class="card stats-card h-100 text-center p-4">
                    <i class="bi bi-boxes fs-1 opacity-75 mb-3"></i>
                    <h3 class="fs-3 fw-bold">{{ $products->total() }}</h3>
                    <p class="mb-0">إجمالي المنتجات</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100 text-center p-4">
                    <i class="bi bi-star-fill fs-1 opacity-75 mb-3"></i>
                    <h3 class="fs-3 fw-bold">{{ $products->where('featured', true)->count() }}</h3>
                    <p class="mb-0">المنتجات المميزة</p>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="category_id" class="form-select">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>فلترة
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>الفئة</th>
                            <th>السعر</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $index => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $index }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-img shadow-sm">
                                    @else
                                        <div class="product-img bg-light d-flex align-items-center justify-content-center text-muted small rounded">
                                            بدون صورة
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark px-2 py-1">
                                        {{ $product->category ? $product->category->name : 'غير محدد' }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-success fs-5">
                                        {{ number_format($product->price, 0) }} ل.س
                                    </strong>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $product->created_at->format('Y-m-d') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 empty-state">
                                    <i class="bi bi-boxes display-1 opacity-25 mb-4"></i>
                                    <h4 class="text-muted mb-3">لا توجد منتجات</h4>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-lg">
                                        <i class="bi bi-plus-lg me-2"></i>أضف أول منتج
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
                <div class="p-3 bg-light border-top">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
</body>
</html>

