<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'    => 'TechDistribuidor S.A.',
                'email'   => 'contacto@techdist.com',
                'phone'   => '555-1001',
                'address' => 'Av. Tecnología 123',
            ],
            [
                'name'    => 'Modas Global',
                'email'   => 'ventas@modasglobal.com',
                'phone'   => '555-2002',
                'address' => 'Calle Moda 456',
            ],
            [
                'name'    => 'Alimentos del Norte',
                'email'   => 'pedidos@alimnorte.com',
                'phone'   => '555-3003',
                'address' => 'Blvd. Norte 789',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
