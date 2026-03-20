<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $products = $query->paginate(10);
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'specifications' => 'nullable|string',
            'additional_info' => 'nullable|string',
'available_colors' => 'nullable|json|array',
            'sizes_available' => 'nullable|json|array',
            'why_choose_it' => 'nullable|string',
            'materials' => 'nullable|string',
            'care_instructions' => 'nullable|string',
            'origin' => 'nullable|string',
'warranty' => 'nullable|string',
            'stock_per_variant' => 'nullable|integer|min:0',
            'variant_images' => 'nullable|json',
        ]);

        // Ensure arrays are JSON encoded
        $data['available_colors'] = json_encode($data['available_colors'] ?? []);
        $data['sizes_available'] = json_encode($data['sizes_available'] ?? []);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

return redirect()->route('admin.products.index')->with('success', 'تم إضافة المنتج بنجاح! 🎉');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'specifications' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'available_colors' => 'nullable|string',
            'sizes_available' => 'nullable|string',
            'why_choose_it' => 'nullable|string',
            'materials' => 'nullable|string',
            'care_instructions' => 'nullable|string',
            'origin' => 'nullable|string',
'warranty' => 'nullable|string',
            'stock_per_variant' => 'nullable|integer|min:0',
            'variant_images' => 'nullable|json',
        ]);

        // Ensure arrays are JSON encoded
        $data['available_colors'] = json_encode($data['available_colors'] ?? []);
        $data['sizes_available'] = json_encode($data['sizes_available'] ?? []);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح!');
    }

    // Categories index with search
    public function categoriesIndex(Request $request)
    {
        $query = Category::latest();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $categories = $query->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    // Discounts CRUD
    public function discountsIndex(Request $request)
    {
        $query = DiscountCode::with('product')->latest();

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $discounts = $query->paginate(15);
        $products = Product::all();

        return view('admin.discounts.index', compact('discounts', 'products'));
    }

    public function discountsCreate()
    {
        $products = Product::all();
        return view('admin.discounts.create', compact('products'));
    }

    public function discountsStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discount_codes,code|min:3|max:20',
            'product_id' => 'required|exists:products,id',
            'percentage' => 'required|numeric|min:1|max:100',
            'uses_limit' => 'nullable|integer|min:0',
            'status' => 'in:active,inactive',
        ]);

        $validated['status'] = $validated['status'] ?? 'active';
        $validated['code'] = strtoupper($validated['code']);

        DiscountCode::create($validated);

        return redirect()->route('admin.discounts.index')->with('success', 'تم إضافة الكود بنجاح!');
    }

    public function discountsEdit(DiscountCode $discount)
    {
        $products = Product::all();
        return view('admin.discounts.edit', compact('discount', 'products'));
    }

    public function discountsUpdate(Request $request, DiscountCode $discount)
    {
        $request->validate([
            'code' => 'required|string|unique:discount_codes,code,' . $discount->id,
            'product_id' => 'required|exists:products,id',
            'percentage' => 'required|numeric|min:1|max:100',
            'uses_limit' => 'nullable|integer|min:0',
            'expires_at' => 'nullable|date',
            'status' => 'in:active,inactive',
        ]);

        $discount->update($request->all());

        return redirect()->route('admin.discounts.index')->with('success', 'تم التحديث بنجاح!');
    }

    public function discountsDestroy(DiscountCode $discount)
    {
        $discount->delete();
        return redirect()->route('admin.discounts.index')->with('success', 'تم الحذف بنجاح!');
    }

    // Reviews CRUD
    public function reviewsIndex(Request $request)
    {
        $query = Review::with(['product', 'user'])->latest();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', '%' . $search . '%')
                  ->orWhereHas('product', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('approved')) {
            $query->where('approved', $request->approved);
        }
        
        $reviews = $query->paginate(15);
        $products = Product::all();
        
        return view('admin.reviews.index', compact('reviews', 'products'));
    }

    public function reviewsEdit(Review $review)
    {
        $review->load(['product', 'user']);
        return view('admin.reviews.edit', compact('review'));
    }

    public function reviewsUpdate(Request $request, Review $review)
    {
        $request->validate([
            'guest_name' => 'nullable|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'approved' => 'boolean',
        ]);

        $review->update($request->only('guest_name', 'rating', 'comment', 'approved'));

        return redirect()->route('admin.reviews.index')->with('success', 'تم تحديث التقييم بنجاح!');
    }

    public function reviewsDestroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'تم حذف التقييم بنجاح!');
    }

    public function reviewsApprove(Review $review)
    {
        $review->update(['approved' => true]);
        return redirect()->route('admin.reviews.index')->with('success', 'تم اعتماد التقييم!');
    }

    public function reviewsReject(Review $review)
    {
        $review->update(['approved' => false]);
        return redirect()->route('admin.reviews.index')->with('success', 'تم رفض التقييم!');
    }
}


