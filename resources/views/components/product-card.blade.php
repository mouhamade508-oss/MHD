<div class="col-lg-3 col-md-6 col-sm-12 mb-4">
  <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden product-card-clean" style="transition: all 0.3s ease; background: #fff;">
    
    <!-- Image: Compact 280px -->
    <div class="position-relative overflow-hidden" style="height: 280px;">
      @if($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top w-100 h-100" alt="{{ $product->name }}" style="object-fit: cover; transition: transform 0.4s ease;">
      @else
        <div class="w-100 h-100 bg-primary d-flex align-items-center justify-content-center text-white" style="font-size: 3rem; font-weight: bold;">
          {{ substr($product->name, 0, 1) }}
        </div>
      @endif

      <!-- Hover overlay buttons (compact, optional) -->
      <div class="position-absolute top-50 start-50 translate-middle d-none d-md-flex gap-1 opacity-0 product-overlay-buttons" style="transition: all 0.3s ease; z-index: 2;">
        <button class="btn btn-light btn-sm p-2 rounded-circle shadow" onclick="toggleHeart(this)" title="إضافة للمفضلة" style="width: 44px; height: 44px;">
          <i class="bi bi-heart fs-6"></i>
        </button>
        <a href="{{ route('product.whatsapp', $product) }}" class="btn btn-success btn-sm p-2 rounded-circle shadow" target="_blank" title="واتساب" style="width: 44px; height: 44px;">
          <i class="bi bi-whatsapp fs-6"></i>
        </a>
      </div>
    </div>
    
    <!-- Clean Content: Tight spacing -->
    <div class="card-body p-3 p-md-4">
      <!-- Compact category & rating -->
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="badge bg-primary-subtle text-primary px-2 py-1 small rounded-pill">{{ $product->category->name ?? 'عام' }}</span>
        <div class="text-warning small d-flex align-items-center">
          <i class="bi bi-star-fill me-1"></i><i class="bi bi-star-fill me-1"></i><i class="bi bi-star-fill me-1"></i><i class="bi bi-star-fill me-1"></i><i class="bi bi-star"></i>
          <span class="text-muted ms-1">(12)</span>
        </div>
      </div>
      
      <!-- Title & Short desc -->
      <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 1rem; line-height: 1.3;">{{ Str::limit($product->name, 45) }}</h5>
      <p class="card-text text-muted small mb-3 line-clamp-2" style="font-size: 0.85rem;">{{ Str::limit($product->description, 80) }}</p>
      
      <!-- Prominent Price -->
      <div class="mb-4">
        @php 
          $discount_pct = $product->discount_percentage ?? 0; 
          $sale_price = $product->price * (1 - $discount_pct / 100); 
        @endphp
        @if($discount_pct > 0)
          <small class="text-muted text-decoration-line-through me-2">ل.س. {{ number_format($product->price, 0) }}</small>
        @endif
        <div class="h5 fw-bold text-primary mb-0">ل.س. {{ number_format($sale_price, 0) }}</div>
        @if($discount_pct > 0)
          <span class="badge bg-success py-1 px-2 small">{{ $discount_pct }}% خصم</span>
        @endif

        @php
          $colorsCount = is_array($product->available_colors) ? count($product->available_colors) : count(json_decode($product->available_colors, true) ?? []);
          $sizesCount = is_array($product->sizes_available) ? count($product->sizes_available) : count(json_decode($product->sizes_available, true) ?? []);
          $totalVariants = $colorsCount * $sizesCount;
        @endphp
        @if($totalVariants > 1)
          <div class="mt-2">
            <span class="badge bg-info text-dark py-1 px-2 small"> {{ $colorsCount }} لون • {{ $sizesCount }} مقاس</span>
          </div>
        @endif
      </div>
      
      <!-- CLEAR Buttons: Vertical full-width, prominent & FIXED -->
      <div class="d-grid gap-2">
        <a href="{{ route('product.show', $product) }}" class="btn btn-primary rounded-3 py-2 fw-semibold" style="font-size: 0.95rem;">
          <i class="bi bi-eye me-1"></i>عرض التفاصيل
        </a>

        <!-- Quick Review Modal with Form -->

        {{-- Review feature removed --}}


      </div>
    </div>
  </div>
</div>

<style>
.product-card-clean {
  border-radius: 15px !important;
}
.product-card-clean:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
}
.product-card-clean:hover .card-img-top {
  transform: scale(1.05);
}
.product-card-clean:hover .product-overlay-buttons {
  opacity: 1 !important;
}
.product-overlay-buttons .btn:hover {
  transform: scale(1.1);
}
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
@media (max-width: 768px) {
  .product-overlay-buttons { display: none !important; }
}
</style>

<script>
function toggleHeart(btn) {
  const icon = btn.querySelector('i');
  icon.classList.toggle('bi-heart-fill');
  icon.classList.toggle('bi-heart');
}
</script>
