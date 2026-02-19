<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->get();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:suppliers',
            'phone'   => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $supplier = Supplier::create($request->all());
        return response()->json($supplier, 201);
    }

    public function show(Supplier $supplier)
    {
        return response()->json($supplier->load('products'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:suppliers,email,' . $supplier->id,
            'phone'   => 'nullable|string',
            'address' => 'nullable|string',
            'active'  => 'boolean',
        ]);

        $supplier->update($request->all());
        return response()->json($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(['message' => 'Proveedor eliminado']);
    }
}
