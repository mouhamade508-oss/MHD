<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إضافة كود خصم جديد - MHD Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --mhd-primary: #1F3C88; }
        .btn-primary { background: var(--mhd-primary); border-color: var(--mhd-primary); }
        .form-card { box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 12px; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('admin.products.index') }}">MHD Admin</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.discounts.index') }}">← العودة للأكواد</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-card p-4">
                    <h2 class="h3 fw-bold mb-4 text-center">إضافة كود خصم جديد</h2>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.discounts.store') }}">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">كود الخصم <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       value="{{ old('code') }}" placeholder="مثال: SALE20" required maxlength="20">
                                <div class="form-text">سيتم تحويله تلقائياً إلى حروف كبيرة (min:3, max:20)</div>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">المنتج <span class="text-danger">*</span></label>
                                <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                    <option value="">اختر المنتج...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} ({{ $product->price }} ر.س)
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">نسبة الخصم % <span class="text-danger">*</span></label>
                                <input type="number" name="percentage" class="form-control @error('percentage') is-invalid @enderror" 
                                       value="{{ old('percentage') }}" min="1" max="100" step="0.5" required>
                                @error('percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">حد أقصى استخدامات</label>
                                <input type="number" name="uses_limit" class="form-control @error('uses_limit') is-invalid @enderror" 
                                       value="{{ old('uses_limit') }}" min="0" placeholder="0 = غير محدود">
                                @error('uses_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">تاريخ الانتهاء</label>
                                <input type="date" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" 
                                       value="{{ old('expires_at') }}">
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">الحالة</label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="status" value="active" class="form-check-input" id="status" {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">نشط</label>
                                </div>
                                <div class="form-text">سيتم تعيينه نشط افتراضياً</div>
                            </div>
                        </div>

                        <div class="d-grid gap-3 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-tag-plus"></i> إنشاء الكود
                            </button>
                            <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary">
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

