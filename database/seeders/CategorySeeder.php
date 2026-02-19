<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electrónica',   'description' => 'Dispositivos electrónicos'],
            ['name' => 'Ropa',          'description' => 'Prendas de vestir'],
            ['name' => 'Alimentos',     'description' => 'Productos alimenticios'],
            ['name' => 'Herramientas',  'description' => 'Herramientas y equipos'],
            ['name' => 'Oficina',       'description' => 'Suministros de oficina'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
