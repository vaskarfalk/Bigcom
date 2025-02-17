<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['category_id', 'brand_id', 'name', 'slug', 'image', 'images', 'description', 'price', 'is_featured', 'in_stock', 'is_active'];
    protected $casts = [
        
        'images' => 'array'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function orderItem()
    {
        return $this->hasMany(OrderItem::class);
    }
}
