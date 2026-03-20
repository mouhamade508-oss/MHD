<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تعديل كود خصم - MHD Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --mhd-primary: #1F3C88;
        }
        .btn-primary { background: var(--mhd-primary); }
        .form-card { box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 12px; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.discounts.index') }}">MHD Admin</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.discounts.index') }}">أكواد الخصم</a>
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
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary mb-3">
                    <i class="bi bi-arrow-right"></i> العودة للقائمة
                </a>
                <div class="card form-card">
                    <div class="card-body p-5">
                        <h2 class="card-title fw-bold mb-4">تعديل كود الخصم <code class="text-primary">{{ $discount->code }}</code></h2>
                        <form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">كود الخصم <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">CODE</span>
                                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $discount->code) }}" required>
                                    </div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">المنتج <span class="text-danger">*</span></label>
                                    <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                        <option value="">اختر المنتج</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id', $discount->product_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">الخصم % <span class="text-danger">*</span></label>
                                    <input type="number" name="percentage" class="form-control @error('percentage') is-invalid @enderror" step="0.01" min="0" max="100" value="{{ old('percentage', $discount->percentage) }}" required>
                                    @error('percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">حد الاستخدامات</label>
                                    <input type="number" name="uses_limit" class="form-control @error('uses_limit') is-invalid @enderror" min="0" value="{{ old('uses_limit', $discount->uses_limit) }}">
                                    @error('uses_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">تاريخ الانتهاء</label>
                                    <input type="date" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at', $discount->expires_at ? $discount->expires_at->format('Y-m-d') : '') }}">
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">الحالة</label>
                                    <select name="status" class="form-select">
                                        <option value="active" {{ old('status', $discount->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ old('status', $discount->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                </div>
                                <div class="col-12 d-grid gap-2 d-md-flex justify-content-end">
                                    <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
                                    <button type="submit" class="btn btn-primary px-4">تحديث الكود</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
