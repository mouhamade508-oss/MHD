<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'product_id',
        'percentage',
        'uses_limit',
        'used_count',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($discount) {
            $discount->code = strtoupper($discount->code);
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeValid($query)
    {
        return $query->where('status', 'active')
                     ->where(function ($q) {
                         $q->whereNull('uses_limit')
                           ->orWhereColumn('used_count', '<', 'uses_limit');
                     })
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', Carbon::now());
                     });
    }

    public function isValid()
    {
        return $this->status === 'active'
            && ($this->uses_limit === null || $this->used_count < $this->uses_limit)
            && (!$this->expires_at || $this->expires_at->isFuture());
    }

    public function use()
    {
        if ($this->isValid()) {
            $this->increment('used_count');
            return true;
        }
        return false;
    }
}

