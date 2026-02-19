<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function summary()
    {
        return response()->json([
            'total_products'   => Product::count(),
            'total_categories' => Category::count(),
            'total_suppliers'  => Supplier::count(),
            'low_stock'        => Product::whereColumn('stock', '<=', 'stock_min')->count(),
            'total_value'      => Product::sum(DB::raw('price * stock')),
        ]);
    }

    public function lowStock()
    {
        $products = Product::with(['category', 'supplier'])
            ->whereColumn('stock', '<=', 'stock_min')
            ->get();

        return response()->json($products);
    }

    public function byCategory()
    {
        $data = Category::withCount('products')
            ->withSum('products', 'stock')
            ->get();

        return response()->json($data);
    }
}
