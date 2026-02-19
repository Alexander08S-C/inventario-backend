<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name', 'sku', 'description', 'price',
        'cost', 'stock', 'stock_min', 'image',
        'category_id', 'supplier_id', 'active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements()
{
    return $this->hasMany(StockMovement::class);
}
}
