<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($sales);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'notes'         => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $total = 0;
        $itemsData = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => "Stock insuficiente para {$product->name}. Stock actual: {$product->stock}"
                ], 422);
            }

            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;

            $itemsData[] = [
                'product'  => $product,
                'quantity' => $item['quantity'],
                'price'    => $product->price,
                'subtotal' => $subtotal,
            ];
        }

        $sale = Sale::create([
            'user_id'       => auth()->id(),
            'customer_name' => $request->customer_name,
            'total'         => $total,
            'notes'         => $request->notes,
        ]);

        foreach ($itemsData as $item) {
            $sale->items()->create([
                'product_id' => $item['product']->id,
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'subtotal'   => $item['subtotal'],
            ]);

            $stockBefore = $item['product']->stock;
            $stockAfter  = $stockBefore - $item['quantity'];

            $item['product']->update(['stock' => $stockAfter]);

            StockMovement::create([
                'product_id'   => $item['product']->id,
                'user_id'      => auth()->id(),
                'type'         => 'salida',
                'quantity'     => $item['quantity'],
                'stock_before' => $stockBefore,
                'stock_after'  => $stockAfter,
                'reason'       => "Venta #{$sale->id}",
            ]);
        }

        return response()->json($sale->load(['user', 'items.product']), 201);
    }

    public function show(Sale $sale)
    {
        return response()->json($sale->load(['user', 'items.product']));
    }

    public function cancel(Sale $sale)
    {
        if ($sale->status === 'cancelada') {
            return response()->json(['message' => 'La venta ya está cancelada'], 422);
        }

        foreach ($sale->items as $item) {
            $product = $item->product;
            $stockBefore = $product->stock;
            $stockAfter  = $stockBefore + $item->quantity;

            $product->update(['stock' => $stockAfter]);

            StockMovement::create([
                'product_id'   => $product->id,
                'user_id'      => auth()->id(),
                'type'         => 'entrada',
                'quantity'     => $item->quantity,
                'stock_before' => $stockBefore,
                'stock_after'  => $stockAfter,
                'reason'       => "Cancelación venta #{$sale->id}",
            ]);
        }

        $sale->update(['status' => 'cancelada']);

        return response()->json(['message' => 'Venta cancelada correctamente']);
    }
}
