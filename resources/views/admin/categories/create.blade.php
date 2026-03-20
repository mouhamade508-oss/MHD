<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إضافة فئة - MHD Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --mhd-primary: #1F3C88; }
        .btn-primary { background: var(--mhd-primary); border-color: var(--mhd-primary); }
        .navbar-brand-logo { background: var(--mhd-primary); color: white; border-radius: 8px; font-weight: 700; }
        .form-card { box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 12px; }
        .color-preview { width: 40px; height: 40px; border-radius: 6px; border: 2px solid #dee2e6; cursor: pointer; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center me-3" href="{{ route('admin.categories.index') }}">
                <span class="navbar-brand-logo me-2 fs-5">MHD</span>
                <span>إدارة الفئات</span>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.products.index') }}">المنتجات</a>
                <a class="nav-link" href="{{ route('admin.categories.index') }}">الفئات</a>
                <span class="navbar-text me-2">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">خروج</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">إضافة فئة جديدة</h1>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-right me-2"></i>العودة
                    </a>
                </div>

                <div class="card form-card">
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('admin.categories.store') }}">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">اسم الفئة <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">الـ Slug (اختياري)</label>
                                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">الوصف (اختياري)</label>
                                    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">لون الفئة (اختياري)</label>
                                    <div class="input-group">
                                        <input type="color" name="color" class="form-control form-control-color @error('color') is-invalid @enderror" value="{{ old('color', '#1F3C88') }}" title="اختر لون">
                                        <span class="input-group-text">معاينة</span>
                                    </div>
                                    <div class="color-preview mt-2 d-inline-block" style="background-color: {{ old('color', '#1F3C88') }};" id="colorPreview"></div>
                                    @error('color')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 d-grid gap-2 d-md-flex justify-content-end">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
                                    <button type="submit" class="btn btn-primary px-4">حفظ الفئة</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Color preview update
        document.querySelector('input[name="color"]').addEventListener('input', function() {
            document.getElementById('colorPreview').style.backgroundColor = this.value;
        });
    </script>
</body>
</html>

