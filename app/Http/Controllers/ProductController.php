<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display the homepage with all products.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        $searchTerm = null;
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Multi categories
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // Price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price Asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);
        $categories = \App\Models\Category::all();

        // Featured products for carousel (fixed, not random)
        $featuredProducts = Product::where('featured', true)
            ->with('category')
            ->limit(10)
            ->get();

        $pageTitle = $searchTerm ? 'نتائج البحث عن: ' . $searchTerm : 'جميع المنتجات';

        return view('products.index', compact('products', 'categories', 'searchTerm', 'pageTitle', 'featuredProducts'));

    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Handle variant selection
        $selectedColor = request('color', session('product_variant_color_' . $product->id));
        $selectedSize = request('size', session('product_variant_size_' . $product->id));
        $stock = $product->variant_stock;

        if ($selectedColor) session(['product_variant_color_' . $product->id => $selectedColor]);
        if ($selectedSize) session(['product_variant_size_' . $product->id => $selectedSize]);

        // Clear discount if requested
        if (request()->has('_clear_discount')) {
            session()->forget('product_discount_' . $product->id);
            return back()->with('success', 'تم إلغاء الخصم');
        }

        $related = Product::where('category_id', $product->category_id)
                         ->where('id', '!=', $product->id)
                         ->limit(4)
                         ->get();

        $reviews = $product->approvedReviews()->latest()->with('user')->paginate(10);
        $avgRating = $product->reviews()->avg('rating') ?? 0;
        $reviewCount = $product->reviews()->count();
        $hasReviewed = Auth::check() ? $product->reviews()->where('user_id', Auth::id())->exists() : false;

        return view('products.show', compact('product', 'related', 'reviews', 'avgRating', 'reviewCount', 'hasReviewed', 'selectedColor', 'selectedSize', 'stock'));
    }

    /**
     * Store new review for product.
     */
    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
            'guest_name' => 'nullable|string|max:50',
        ]);

        Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'guest_name' => $request->guest_name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'approved' => false, // Pending admin approval
        ]);

        return back()->with('success', 'تم إرسال تقييمك بنجاح! سيظهر بعد موافقة الإدارة.');
    }


    /**
     * Redirect to WhatsApp order link for a product.
     */
    public function whatsapp(Product $product)
    {
        $whatsappNumber = env('WHATSAPP_NUMBER', '963XXXXXXXXX');
        $selectedColor = session('product_variant_color_' . $product->id);
        $selectedSize = session('product_variant_size_' . $product->id);
        $stock = $product->variant_stock;

        $variantInfo = '';
        if ($selectedColor || $selectedSize) {
            $variantInfo .= "اللون: " . ($selectedColor ?? 'غير محدد') . " | ";
            $variantInfo .= "المقاس: " . ($selectedSize ?? 'غير محدد') . " | ";
            $variantInfo .= "المخزون: {$stock}";
        }

        $message = "مرحباً، أريد طلب: {$product->name}\n{$variantInfo}\n - MHD Print Lab";
        $encodedMessage = urlencode($message);
        return redirect("https://wa.me/{$whatsappNumber}?text={$encodedMessage}");
    }

    /**
     * Generate WhatsApp order URL for a product.
     */
    public function getWhatsAppLink(Product $product): string
    {
        $whatsappNumber = env('WHATSAPP_NUMBER', '963XXXXXXXXX');
        $message = "مرحباً، أريد الاستفسار عن منتج: {$product->name} - MHD Print Lab";
        $encodedMessage = urlencode($message);
        return "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";
    }

    /**
     * Apply discount code for product (New form-based method - No JS).
     */
    public function applyDiscount(Request $request, Product $product)
    {
        $request->validate([
            'discount_code' => 'required|string|max:20'
        ]);

        $discount = \App\Models\DiscountCode::valid()
            ->where('code', strtoupper($request->discount_code))
            ->where('product_id', $product->id)
            ->first();

        if (!$discount) {
            return back()->with('error', 'كود الخصم غير صالح أو منتهي الصلاحية لهذا المنتج.');
        }

        // Store discount in session
        session(['product_discount_' . $product->id => [
            'code' => $discount->code,
            'percentage' => $discount->percentage,
            'new_price' => round($product->price * (1 - $discount->percentage / 100), 0)
        ]]);

        $discountData = [
            'code' => $discount->code,
            'percentage' => $discount->percentage,
            'new_price' => round($product->price * (1 - $discount->percentage / 100), 0)
        ];
        session(['product_discount_' . $product->id => $discountData]);
        return back()->with('success', "تم تطبيق خصم {$discount->percentage}% بنجاح! السعر الجديد: ل.س " . number_format($discountData['new_price']));
    }

}

