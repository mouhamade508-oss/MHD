<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة الفئات - MHD Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --mhd-primary: #1F3C88;
            --mhd-primary-light: #3B5BBE;
        }
        .btn-primary { background: var(--mhd-primary); border-color: var(--mhd-primary); }
        .btn-primary:hover { background: var(--mhd-primary-light) !important; }
        .navbar-brand-logo { background: var(--mhd-primary); color: white; border-radius: 8px; font-weight: 700; padding: 0.5rem 1rem; }
        .table-card { box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 12px; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center me-3" href="{{ route('admin.products.index') }}">
                <span class="navbar-brand-logo me-2 fs-5">MHD</span>
                <span>لوحة الإدارة</span>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.products.index') }}">المنتجات</a>
                <a class="nav-link active" href="{{ route('admin.categories.index') }}">الفئات</a>
                <span class="navbar-text me-2">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">خروج</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col">
                <h1 class="h3 mb-1">إدارة الفئات</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus me-2"></i>فئة جديدة
                </a>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>بحث
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100">مسح</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card table-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>عدد المنتجات</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $index => $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $index }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><span class="badge bg-primary">{{ $category->products_count }}</span></td>
                                <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                            تعديل
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">حذف</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-tags display-4 opacity-50 mb-3"></i>
                                    <h5>لا توجد فئات</h5>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">إضافة أول فئة</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
                <div class="p-3 bg-light border-top">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
