<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة التقييمات - MHD Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="h4 fw-bold text-dark">إدارة التقييمات والتعليقات</h2>
        </x-slot>

        <div class="container-fluid">
            <!-- Search & Filter -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="ابحث في التعليق أو اسم المنتج..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="product_id" class="form-select">
                                    <option value="">جميع المنتجات</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ Str::limit($product->name, 30) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="approved" class="form-select">
                                    <option value="">الحالة</option>
                                    <option value="1" {{ request('approved') == '1' ? 'selected' : '' }}>معتمد</option>
                                    <option value="0" {{ request('approved') == '0' ? 'selected' : '' }}>غير معتمد</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">بحث</button>
                                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">مسح</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle display-6 opacity-75 mb-2"></i>
                            <h3>{{ $reviews->where('approved', true)->count() }}</h3>
                            <p class="mb-0">معتمدة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white shadow">
                        <div class="card-body text-center">
                            <i class="bi bi-clock display-6 opacity-75 mb-2"></i>
                            <h3>{{ $reviews->where('approved', false)->count() }}</h3>
                            <p class="mb-0">في الانتظار</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white shadow">
                        <div class="card-body text-center">
                            <i class="bi bi-x-circle display-6 opacity-75 mb-2"></i>
                            <h3>0</h3>
                            <p class="mb-0">مرفوضة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white shadow">
                        <div class="card-body text-center">
                            <i class="bi bi-star display-6 opacity-75 mb-2"></i>
                            <h3>{{ number_format($reviews->avg('rating'), 1) }}</h3>
                            <p class="mb-0">متوسط التقييم</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="card shadow">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">جميع التقييمات ({{ $reviews->total() }})</h5>
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($reviews as $review)
                        <div class="border-bottom p-4 hover-shadow">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="text-center">
                                        @if($review->user)
                                            {{ $review->user->name }}
                                        @else
                                            {{ $review->guest_name ?: 'زائر' }}
                                        @endif
                                        <br><small class="text-muted">{{ $review->created_at->format('Y/m/d') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('admin.products.edit', $review->product) }}" class="text-decoration-none">
                                        {{ Str::limit($review->product->name, 25) }}
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-warning">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} fs-5"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">{{ $review->rating }}/5</small>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1 fw-medium">{{ Str::limit($review->comment, 80) }}</p>
                                    @if(strlen($review->comment) > 80)
                                        <button class="btn btn-sm btn-link p-0 text-primary">المزيد...</button>
                                    @endif
                                </div>
                                <div class="col-md-2 text-end">
                                    <span class="badge {{ $review->approved ? 'bg-success' : 'bg-warning' }}">
                                        {{ $review->approved ? 'معتمد' : 'في الانتظار' }}
                                    </span>
                                    <div class="btn-group btn-group-sm mt-2" role="group">
                                        @if(!$review->approved)
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display: inline;">
                                                @csrf @method('POST')
                                                <button type="submit" class="btn btn-success" title="اعتماد">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" style="display: inline;">
                                            @csrf @method('POST')
                                            <button type="submit" class="btn btn-warning" title="رفض">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-primary" title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" style="display: inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('متأكد؟')" title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-star display-1 text-muted mb-3"></i>
                            <h5>لا توجد تقييمات بعد</h5>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            // Hover effect
            document.querySelectorAll('.hover-shadow').forEach(el => {
                el.addEventListener('mouseenter', () => el.parentElement.style.boxShadow = '0 0.5rem 1rem rgba(0,0,0,.15)');
                el.addEventListener('mouseleave', () => el.parentElement.style.boxShadow = 'none');
            });
        </script>
        @endpush
    </x-app-layout>
</body>
</html>

