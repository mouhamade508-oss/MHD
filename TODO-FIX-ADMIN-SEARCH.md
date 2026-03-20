# TODO: Fix Admin Panel Search & Filters

Status: 🔄 IN PROGRESS

## Approved Plan Steps

1. ✅ **Update AdminController.php**
    - Add category_id filter to products@index()
    - Add categoriesIndex() method with name search

2. ✅ **Update resources/views/admin/categories/index.blade.php**
    - Add search form
    - Fix pagination with $categories variable
    - Update table numbering

3. [ ] **Verify routes/web.php**
    - Ensure admin.categories.index points to categoriesIndex

4. [ ] **Test & Cleanup**
    - php artisan route:clear
    - php artisan view:clear
    - Test products category filter
    - Test categories name search

5. [ ] **Verify routes/web.php**
    - Ensure admin.categories.index points to categoriesIndex

6. [ ] **Test & Cleanup**
    - php artisan route:clear
    - php artisan view:clear
    - Test products category filter
    - Test categories name search

**Verification:**

- Products: search name + filter category works
- Categories: search by name works

**Completed by:** BLACKBOXAI
