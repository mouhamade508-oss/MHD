# إعادة بناء ميزة أكواد الخصم - خطوات التنفيذ

## Phase 1: حذف النسخة الحالية

- [ ]   1. حذف app/Models/DiscountCode.php
- [ ]   2. حذف resources/views/admin/discounts/\*.blade.php (3 files)
- [ ]   3. حذف TODO-FIX-DISCOUNTS-INDEX.md
- [ ]   4. إزالة discounts methods من AdminController.php
- [ ]   5. إزالة validateDiscount من ProductController.php
- [ ]   6. إزالة discount routes من routes/web.php
- [ ]   7. migration لـ drop discount_codes table

## Phase 2: إعادة البناء

- [ ]   8. new migration create_discount_codes_table (w/ status)
- [ ]   9. new DiscountCode model (improved)
- [ ]   10. DiscountCodeSeeder
- [ ]   11. update DatabaseSeeder
- [ ]   12. enhanced AdminController discounts CRUD
- [ ]   13. enhanced ProductController validateDiscount
- [ ]   14. routes
- [ ]   15. improved admin views
- [ ]   16. fix products/show JS

## Testing

- [ ]   17. migrate:fresh --seed
- [ ]   18. test admin CRUD
- [ ]   19. test frontend
- [ ]   20. ✅ complete
