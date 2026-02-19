<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        $permisos = [
            'ver productos', 'crear productos', 'editar productos', 'eliminar productos',
            'ver categorias', 'crear categorias', 'editar categorias', 'eliminar categorias',
            'ver proveedores', 'crear proveedores', 'editar proveedores', 'eliminar proveedores',
            'ver reportes',
        ];

        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $empleado = Role::create(['name' => 'empleado']);

        // Admin tiene todos los permisos
        $admin->givePermissionTo(Permission::all());

        // Empleado permisos limitados
        $empleado->givePermissionTo([
            'ver productos', 'crear productos',
            'ver categorias',
            'ver proveedores',
            'ver reportes',
        ]);

        // Crear usuario admin
        $userAdmin = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@inventario.com',
            'password' => bcrypt('password123'),
        ]);
        $userAdmin->assignRole('admin');

        // Crear usuario empleado
        $userEmpleado = User::create([
            'name'     => 'Empleado Demo',
            'email'    => 'empleado@inventario.com',
            'password' => bcrypt('password123'),
        ]);
        $userEmpleado->assignRole('empleado');
    }
}
