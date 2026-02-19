<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'supplier'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%")
            )
            ->when($request->category_id, fn($q) =>
                $q->where('category_id', $request->category_id)
            )
            ->when($request->supplier_id, fn($q) =>
                $q->where('supplier_id', $request->supplier_id)
            )
            ->paginate(15);

        return response()->json($products);
    }

    public function store(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'sku'         => 'required|string|unique:products',
        'price'       => 'required|numeric|min:0',
        'cost'        => 'nullable|numeric|min:0',
        'stock'       => 'required|integer|min:0',
        'stock_min'   => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'image'       => 'nullable|image|max:2048',
    ]);

    $data = $request->except('image');

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product = Product::create($data);
    return response()->json(['data' => $product->load(['category', 'supplier'])], 201);
}

    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'supplier']));
    }

    public function update(Request $request, Product $product)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'sku'         => 'required|string|unique:products,sku,' . $product->id,
        'price'       => 'required|numeric|min:0',
        'cost'        => 'nullable|numeric|min:0',
        'stock'       => 'required|integer|min:0',
        'stock_min'   => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'image'       => 'nullable|image|max:2048',
    ]);

    $data = $request->except('image');

    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);
    return response()->json(['data' => $product->load(['category', 'supplier'])]);
}
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Producto eliminado']);
    }
}
