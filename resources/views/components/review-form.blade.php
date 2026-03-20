@props(['product', 'hasReviewed' => false])

@if(!$hasReviewed)
<div class="bg-warning bg-opacity-10 border border-warning rounded-3 p-4 mb-4 text-center">
    <h6 class="fw-bold text-warning mb-2">
        <i class="bi bi-star-fill"></i> شارك رأيك في {{ $product->name }}!
    </h6>
    <p class="text-muted small mb-3">ساعد الزبائن الآخرين</p>
    <button class="btn btn-warning w-100 text-white shadow" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $product->id }}">
        <i class="bi bi-star-fill me-2"></i> أضف تقييم + تعليق
    </button>
</div>
@endif

<!-- Modal Form -->
<div class="modal fade" id="reviewModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-star-fill me-2"></i> تقييم {{ $product->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="reviewForm{{ $product->id }}" method="POST" action="{{ route('product.reviews.store', $product) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">الاسم <span class="text-muted">(اختياري)</span></label>
                        <input type="text" name="guest_name" class="form-control" placeholder="اسمك للعرض" maxlength="50" value="{{ old('guest_name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">التقييم <span class="text-danger">*</span></label>
                        <div class="d-flex justify-content-center gap-1 mb-2 stars-container" data-product="{{ $product->id }}">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill fs-2 star-rating text-muted cursor-pointer mx-1" data-rating="{{ $i }}" style="cursor: pointer;" title="{{ $i }} نجمة"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating{{ $product->id }}" value="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">تعليقك <span class="text-danger">*</span></label>
                        <textarea name="comment" rows="4" class="form-control" placeholder="ما رأيك في المنتج؟ جودة، تصميم، مقاس... (10 أحرف كحد أدنى)" required minlength="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="submitReview{{ $product->id }}">
                        <i class="bi bi-send me-2"></i> إرسال التقييم
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
  'use strict';
  
  const productId = {{ $product->id }};
  
  // Scoped to this product
  const modalId = 'reviewModal' + productId;
  const ratingId = 'rating' + productId;
  const submitId = 'submitReview' + productId;
  const formId = 'reviewForm' + productId;
  
  const modal = document.getElementById(modalId);
  if (!modal) return;
  
  const starsContainer = modal.querySelector('.stars-container');
  const stars = modal.querySelectorAll('.star-rating');
  const ratingInput = document.getElementById(ratingId);
  const submitBtn = document.getElementById(submitId);
  const form = document.getElementById(formId);
  
  if (!stars.length || !ratingInput || !submitBtn || !form) return;
  
  let selectedRating = 0;
  
  // Stars interaction
  stars.forEach((star, index) => {
    star.addEventListener('click', function() {
      selectedRating = parseInt(this.dataset.rating);
      updateStars();
    });
    
    star.addEventListener('mouseenter', function() {
      highlightStars(1 + index);
    });
  });
  
  modal.addEventListener('mouseleave', () => {
    updateStars();
  });
  
  function highlightStars(toRating) {
    stars.forEach((star, index) => {
      star.classList.toggle('text-warning', index < toRating);
      star.classList.toggle('text-muted', index >= toRating);
    });
  }
  
  function updateStars() {
    highlightStars(selectedRating);
    ratingInput.value = selectedRating;
    submitBtn.disabled = selectedRating === 0;
  }
  
  // Submit with AJAX
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (selectedRating === 0) {
      alert('يرجى اختيار تقييم');
      return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري الإرسال...';
    
    const formData = new FormData(form);
    
    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Success
        const bsModal = bootstrap.Modal.getInstance(modal);
        bsModal.hide();
        form.reset();
        selectedRating = 0;
        updateStars();
        
        // Toast
        showToast(data.message || 'تم إرسال التقييم بنجاح!', 'success');
      } else {
        alert(data.message || 'خطأ في الإرسال');
      }
    } catch (error) {
      console.error('Review error:', error);
      alert('خطأ في الاتصال، جرب مرة أخرى');
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="bi bi-send me-2"></i> إرسال التقييم';
    }
  });
  
  // Toast function
  function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed translate-middle`;
    toast.style.cssText = 'top: 1rem; right: 1rem; z-index: 1080; min-width: 300px;';
    toast.innerHTML = `
      <div class="d-flex">
        <div class="alert-body flex-grow-1">${message}</div>
        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
      </div>
    `;
    document.body.appendChild(toast);
    
    new bootstrap.Alert(toast);
  }
  
})();
</script>
