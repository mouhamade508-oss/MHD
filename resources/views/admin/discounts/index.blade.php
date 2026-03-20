<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة أكواد الخصم - MHD Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --mhd-primary: #1F3C88;
        }
        .btn-primary { background: var(--mhd-primary); }
        .table-card { box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 12px; }
        .product-img { width: 50px; height: 50px; object-fit: cover; }
        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }
        .copy-btn { opacity: 0.7; transition: opacity 0.2s; }
        .copy-btn:hover { opacity: 1; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.products.index') }}">MHD Admin</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.products.index') }}">المنتجات</a>
                <a class="nav-link" href="{{ route('admin.categories.index') }}">الفئات</a>
                <a class="nav-link active" href="{{ route('admin.discounts.index') }}">أكواد الخصم</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">خروج</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 fw-bold">إدارة أكواد الخصم</h1>
                <small class="text-muted">إجمالي الأكواد: {{ $discounts->total() }}</small>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> إضافة كود جديد
                </a>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="بحث بالكود..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="product_id" class="form-select">
                                <option value="">المنتج</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">الحالة</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">فلتر</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>الكود</th>
                            <th>المنتج</th>
                            <th>الخصم %</th>
                            <th>الاستخدامات</th>
                            <th>الانتهاء</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
@forelse($discounts as $discount)
                        <tr>
                            <td>
                                <code class="text-primary">{{ $discount->code }}</code>
                                <button class="btn btn-sm btn-link p-0 ms-2 copy-btn" onclick="copyCode('{{ $discount->code }}')" title="نسخ">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </td>
                            <td>
                                <img src="{{ $discount->product->image ? asset('storage/' . $discount->product->image) : asset('images/no-image.jpg') }}" class="product-img rounded me-2">
                                {{ Str::limit($discount->product->name, 30) }}
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $discount->percentage }}%</span>
                            </td>
                            <td>
                                {{ $discount->used_count }} / {{ $discount->uses_limit ?? '∞' }}
                            </td>
                            <td>
                                <span class="badge {{ $discount->expires_at && $discount->expires_at->lt(now()->addDays(7)) ? 'bg-warning' : 'bg-secondary' }}">
                                    {{ $discount->expires_at ? $discount->expires_at->format('Y-m-d') : 'لا ينتهي' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $discount->status == 'active' ? 'status-active' : 'status-inactive' }} px-3 py-2 fs-6">
                                    {{ $discount->status == 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.discounts.destroy', $discount) }}" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
@empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-tag fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">لا توجد أكواد خصم</h5>
                                <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">إضافة الأول</a>
                            </td>
                        </tr>
@endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 bg-light">
                {{ $discounts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                const btn = event.target.closest('.copy-btn');
                const originalIcon = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
                btn.style.opacity = 1;
                setTimeout(() => {
                    btn.innerHTML = originalIcon;
                }, 2000);
            });
        }
    </script>
</body>
</html>
