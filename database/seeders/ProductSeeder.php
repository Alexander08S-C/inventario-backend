<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'        => 'Laptop Pro 15',
                'sku'         => 'TECH-001',
                'description' => 'Laptop de alto rendimiento',
                'price'       => 1299.99,
                'cost'        => 900.00,
                'stock'       => 25,
                'stock_min'   => 5,
                'category_id' => 1,
                'supplier_id' => 1,
            ],
            [
                'name'        => 'Teclado Mecánico',
                'sku'         => 'TECH-002',
                'description' => 'Teclado mecánico RGB',
                'price'       => 89.99,
                'cost'        => 45.00,
                'stock'       => 3,
                'stock_min'   => 5,
                'category_id' => 1,
                'supplier_id' => 1,
            ],
            [
                'name'        => 'Camiseta Polo',
                'sku'         => 'ROPA-001',
                'description' => 'Camiseta polo de algodón',
                'price'       => 29.99,
                'cost'        => 12.00,
                'stock'       => 100,
                'stock_min'   => 10,
                'category_id' => 2,
                'supplier_id' => 2,
            ],
            [
                'name'        => 'Arroz Premium 5kg',
                'sku'         => 'ALIM-001',
                'description' => 'Arroz de grano largo',
                'price'       => 8.99,
                'cost'        => 4.50,
                'stock'       => 2,
                'stock_min'   => 10,
                'category_id' => 3,
                'supplier_id' => 3,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
