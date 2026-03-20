<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'discount_percentage',
        'image',
'video',
        'category_id',
        'featured',
        'tags',
'specifications',
        'additional_info',
        'available_colors',
        'sizes_available',
        'why_choose_it',
        'materials',
        'care_instructions',
        'origin',
        'warranty',
    ];


    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'featured' => 'boolean',
        'available_colors' => 'array',
        'sizes_available' => 'array',
        'stock_per_variant' => 'integer',
        'variant_images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get approved reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

public function approvedReviews()
    {
        return $this->reviews()->where('approved', true);
    }

    // Variant accessors for safe array access
    public function getColorsAttribute($value)
    {
        return $value ?? [];
    }

    public function getSizesAttribute($value)
    {
        return $value ?? [];
    }

    public function getVariantStockAttribute()
    {
        return $this->stock_per_variant ?? 10;
    }

    public function getVariantImagesAttribute($value)
    {
        return $value ?? [];
    }

}

