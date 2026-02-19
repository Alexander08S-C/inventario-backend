<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::with(['product', 'user'])
            ->when($request->product_id, fn($q) =>
                $q->where('product_id', $request->product_id)
            )
            ->when($request->type, fn($q) =>
                $q->where('type', $request->type)
            )
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($movements);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:entrada,salida,ajuste',
            'quantity'   => 'required|integer|min:1',
            'reason'     => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $stockBefore = $product->stock;

        if ($request->type === 'entrada') {
            $stockAfter = $stockBefore + $request->quantity;
        } elseif ($request->type === 'salida') {
            if ($request->quantity > $stockBefore) {
                return response()->json(['message' => 'Stock insuficiente'], 422);
            }
            $stockAfter = $stockBefore - $request->quantity;
        } else {
            $stockAfter = $request->quantity;
        }

        $product->update(['stock' => $stockAfter]);

        $movement = StockMovement::create([
            'product_id'   => $product->id,
            'user_id'      => auth()->id(),
            'type'         => $request->type,
            'quantity'     => $request->quantity,
            'stock_before' => $stockBefore,
            'stock_after'  => $stockAfter,
            'reason'       => $request->reason,
        ]);

        return response()->json($movement->load(['product', 'user']), 201);
    }
}
