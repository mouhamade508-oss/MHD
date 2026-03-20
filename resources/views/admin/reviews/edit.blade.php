<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل تقييم - MHD Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-dark mb-0">تعديل التقييم</h2>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-right me-2"></i>العودة للقائمة
            </a>
        </div>
    </x-slot>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="mb-4 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>المنتج:</strong> {{ $review->product->name }}<br>
                                    <strong>التقييم الحالي:</strong> 
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                    {{ $review->rating }}/5
                                </div>
                                <div class="col-md-6">
                                    <strong>العميل:</strong> {{ $review->user->name ?? $review->guest_name ?: 'زائر' }}<br>
                                    <strong>التاريخ:</strong> {{ $review->created_at->format('Y/m/d H:i') }}<br>
                                    <strong>الحالة:</strong> 
                                    <span class="badge {{ $review->approved ? 'bg-success' : 'bg-warning' }}">
                                        {{ $review->approved ? 'معتمد' : 'في الانتظار' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">اسم العميل (اختياري)</label>
                                    <input type="text" name="guest_name" value="{{ $review->guest_name }}" class="form-control @error('guest_name') is-invalid @enderror" maxlength="50">
                                    @error('guest_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">التقييم</label>
                                    <div class="d-flex justify-content-center gap-1 mb-2">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star-fill fs-3 rating-star text-muted cursor-pointer" data-rating="{{ $i }}" style="cursor:pointer"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="rating-input" value="{{ $review->rating }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">التعليق</label>
                                    <textarea name="comment" rows="5" class="form-control @error('comment') is-invalid @enderror" required>{{ $review->comment }}</textarea>
                                    @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">الحالة</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="approved" id="approved" class="form-check-input" value="1" {{ $review->approved ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="approved">معتمد (يظهر للعملاء)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>حفظ التغييرات
                                </button>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('rating-input');
        
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                const rating = index + 1;
                ratingInput.value = rating;
                stars.forEach((s, i) => {
                    s.classList.toggle('text-warning', i < rating);
                    s.classList.toggle('text-muted', i >= rating);
                });
            });
            
            star.addEventListener('mouseenter', () => {
                const rating = index + 1;
                stars.forEach((s, i) => s.classList.toggle('text-warning', i < rating));
            });
            
            star.addEventListener('mouseleave', () => {
                const rating = ratingInput.value;
                stars.forEach((s, i) => s.classList.toggle('text-warning', i < rating));
            });
        });
    </script>
    @endpush
</x-app-layout>
</body>
</html>

