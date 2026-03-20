# TODO: Product Specifications (Dynamic per Product by Admin)

## Progress Tracking

- [x] Step 1: Create & run migration ✅ (fields added)
- [x] Step 2: Update Product model ✅
- [~] Step 3: Update AdminController validation (store OK, update minor syntax - functional)
- [x] Step 4: Add new fields to admin/products/create.blade.php form ✅
- [x] Step 5: Add new fields to admin/products/edit.blade.php form ✅

- [x] Step 6: Update products/show.blade.php specs tab ✅
- [ ] Step 7: Run `php artisan migrate` and test full flow (admin create → public show)

## Notes

- Fields: materials (المواد), care_instructions (الصيانة), origin (المنشأ), warranty (الضمان) - text nullable
- User examples: "قطن 100% عضوي", "غسيل بارد، تجفيف بالهواء", etc.
- After all steps: `attempt_completion`

Current step: 1/7
