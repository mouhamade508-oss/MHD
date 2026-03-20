<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إضافة منتج - MHD Print Lab Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --mhd-primary: #1F3C88;
        }
        .btn-primary { background: var(--mhd-primary); border-color: var(--mhd-primary); }
        .btn-primary:hover { background: #0f2b5f !important; border-color: #0f2b5f; }
        .form-card { border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .navbar-brand-logo { background: var(--mhd-primary); color: white; border-radius: 8px; font-weight: 700; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center me-3" href="#">
                <span class="navbar-brand-logo me-2 px-2 py-1 fs-5">MHD</span>
                <span>لوحة الإدارة</span>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.products.index') }}">المنتجات</a>
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
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">إضافة منتج جديد</h1>
                </div>

                <div class="card form-card">
                    <div class="card-body p-5">
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label fw-bold">اسم المنتج <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">السعر (ل.س) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">الحسم %</label>
                                    <input type="number" name="discount_percentage" class="form-control" step="0.01" min="0" max="100" value="{{ old('discount_percentage', 0) }}" placeholder="0">
                                    <small class="text-muted">0 = بدون خصم</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">الفئة <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">اختر فئة</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">صورة المنتج</label>
                                    <input type="file" name="image" class="form-control">
                                    <small class="text-muted">JPG, PNG (Max 5MB)</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">الوصف <span class="text-danger">*</span></label>
                                    <textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">المواصفات التقنية</label>
                                    <textarea name="specifications" rows="3" class="form-control">{{ old('specifications') }}</textarea>
                                    <small class="text-muted">مثال: المواد المستخدمة، التقنيات، الميزات الفنية</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">معلومات إضافية</label>
                                    <textarea name="additional_info" rows="3" class="form-control">{{ old('additional_info') }}</textarea>
                                    <small class="text-muted">معلومات مفيدة للعميل مثل الاستخدام، الصيانة، إلخ</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">الألوان المتاحة <span class="text-info">(اختر أو أضف hex)</span></label>
                                    <div id="colorContainer" class="mb-2">
                                        <!-- Dynamic colors will be here -->
                                    </div>
                                    <div class="input-group">
                                        <input type="color" id="colorPicker" class="form-control form-control-color" value="#e94560">
                                        <input type="text" id="colorHex" class="form-control" placeholder="#hex code">
                                        <button type="button" class="btn btn-outline-primary" onclick="addColor()">إضافة لون</button>
                                    </div>
                                    <input type="hidden" name="available_colors" id="colorsJson">
                                    <small class="text-muted">اضغط على اللون المضاف للحذفه</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">المقاسات المتاحة</label>
                                    <div class="mb-2">
                                        <label class="form-check-label me-3"><input type="checkbox" class="form-check-input size-check" value="XS"> XS</label>
                                        <label class="form-check-label me-3"><input type="checkbox" class="form-check-input size-check" value="S"> S</label>
                                        <label class="form-check-label me-3"><input type="checkbox" class="form-check-input size-check" value="M"> M</label>
                                        <label class="form-check-label me-3"><input type="checkbox" class="form-check-input size-check" value="L"> L</label>
                                        <label class="form-check-label me-3"><input type="checkbox" class="form-check-input size-check" value="XL"> XL</label>
                                        <label class="form-check-label me-3"><input type="checkbox" class="form-check-input size-check" value="XXL"> XXL</label>
                                    </div>
                                    <div class="input-group mb-2">
                                        <input type="text" id="customSize" class="form-control" placeholder="مقاس مخصص">
                                        <button type="button" class="btn btn-outline-secondary" onclick="addCustomSize()">إضافة</button>
                                    </div>
                                    <input type="hidden" name="sizes_available" id="sizesJson">
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <label class="form-label fw-bold">المخزون لكل مقاس/لون</label>
                                        <input type="number" name="stock_per_variant" class="form-control" value="{{ old('stock_per_variant', 10) }}" min="0" placeholder="10">
                                        <small class="text-muted">عدد القطع المتاحة لكل variant</small>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <label class="form-label fw-bold">صور الـ variants (JSON)</label>
                                        <textarea name="variant_images" class="form-control" rows="3" placeholder='{"#FF0000_M": "path/to/red-medium.jpg"}'>{{ old('variant_images') }}</textarea>
                                        <small class="text-muted">صيغة: {color_size: image_path}</small>
                                    </div>

                                <div class="col-12">
                                    <label class="form-label">لماذا تختاره؟</label>
                                    <textarea name="why_choose_it" rows="4" class="form-control">{{ old('why_choose_it') }}</textarea>
                                    <small class="text-muted">ما الذي يميز هذا المنتج ويجعله الخيار الأفضل؟</small>
                                </div>
                                <div class="row mb-4">
                                    <h5 class="col-12 fw-bold text-primary mb-3">مواصفات المنتج</h5>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">المواد</label>
                                        <input type="text" name="materials" class="form-control" value="{{ old('materials') }}" placeholder="مثال: قطن 100% عضوي">
                                        <small class="text-muted">المواد المستخدمة في المنتج</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">الصيانة</label>
                                        <input type="text" name="care_instructions" class="form-control" value="{{ old('care_instructions') }}" placeholder="مثال: غسيل بارد، تجفيف بالهواء">
                                        <small class="text-muted">تعليمات الصيانة والعناية</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">المنشأ</label>
                                        <input type="text" name="origin" class="form-control" value="{{ old('origin') }}" placeholder="مثال: صنع في سوريا وتركيا">
                                        <small class="text-muted">بلد المنشأ أو المصنع</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">الضمان</label>
                                        <input type="text" name="warranty" class="form-control" value="{{ old('warranty') }}" placeholder="مثال: جودة عالية، ضمان سنة">
                                        <small class="text-muted">معلومات الضمان والجودة</small>
                                    </div>
                                </div>
                                <div class="col-12 d-grid gap-2 d-md-flex justify-content-end">

                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
                                    <button type="submit" class="btn btn-primary px-4">حفظ المنتج</button>
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
        let colors = {{ json_encode(old('available_colors', [])) }};
        let sizes = {{ json_encode(old('sizes_available', [])) }};

        function renderColors() {
            const container = document.getElementById('colorContainer');
            container.innerHTML = '';
            colors.forEach((color, index) => {
                const div = document.createElement('div');
                div.className = 'd-inline-block me-2 mb-2';
                div.innerHTML = `
                    <button type="button" class="btn btn-sm" style="background: ${color}; width: 40px; height: 40px; border-radius: 50%; border: 3px solid #ddd;" 
                            onclick="removeColor(${index})" title="${color}"></button>
                    <small class="d-block text-muted">${color}</small>
                `;
                container.appendChild(div);
            });
        }

        function renderSizes() {
            document.querySelectorAll('.size-check').forEach(cb => {
                cb.checked = sizes.includes(cb.value);
            });
        }

        function addColor() {
            const hex = document.getElementById('colorHex').value.trim();
            if (hex && /^#[0-9A-F]{6}$/i.test(hex)) {
                colors.push(hex);
                document.getElementById('colorHex').value = '';
                renderColors();
                updateJson();
            }
        }

        window.removeColor = function(index) {
            colors.splice(index, 1);
            renderColors();
            updateJson();
        }

window.addCustomSize = function() {
            const size = document.getElementById('customSize').value.trim().toUpperCase();
            if (size && !sizes.includes(size)) {
                sizes.push(size);
                document.getElementById('customSize').value = '';
                renderSizes();
                updateJson();
            }
        }

        function updateJson() {
            document.getElementById('colorsJson').value = JSON.stringify(colors);
            document.getElementById('sizesJson').value = JSON.stringify(sizes);
        }

        // Event listeners
        document.querySelectorAll('.size-check').forEach(cb => {
            cb.addEventListener('change', function() {
                const val = this.value;
                if (this.checked) {
                    if (!sizes.includes(val)) sizes.push(val);
                } else {
                    sizes = sizes.filter(s => s !== val);
                }
                updateJson();
            });
        });

        // Color picker sync
        document.getElementById('colorPicker').addEventListener('change', function() {
            document.getElementById('colorHex').value = this.value;
        });

        document.getElementById('colorHex').addEventListener('input', function() {
            document.getElementById('colorPicker').value = this.value;
        });

        document.getElementById('colorHex').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') addColor();
        });

        document.getElementById('customSize').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') addCustomSize();
        });

        // Form submit - ensure update
        document.querySelector('form').addEventListener('submit', updateJson);

        // Init
        renderColors();
        renderSizes();
        updateJson();
    </script>
</body>
</html>
