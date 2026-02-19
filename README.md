# 🏪 Sistema de Gestión de Inventario - Backend

API REST desarrollada con **Laravel 12 + MySQL + Sanctum**.

## 🚀 Tecnologías
- Laravel 12
- MySQL
- Laravel Sanctum (autenticación)
- Spatie Laravel Permission (roles y permisos)

## ✅ Endpoints principales
- Auth: login, logout, perfil
- Productos: CRUD + imágenes + paginación
- Categorías y Proveedores: CRUD
- Usuarios: CRUD + roles
- Movimientos de Stock
- Ventas
- Reportes: resumen, stock bajo, por categoría

## ⚙️ Instalación
```bash
git clone https://github.com/Alexander08S-C/inventario-backend.git
cd inventario-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## 🔗 Frontend
[inventario-frontend](https://github.com/Alexander08S-C/inventario-frontend)
