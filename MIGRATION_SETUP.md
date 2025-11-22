# 🗄️ Configuración de Base de Datos - Migraciones y Seeders

## Resumen

Este documento describe el proceso de configuración de la base de datos ejecutado exitosamente en el proyecto DiscZone.

---

## ✅ Tareas Completadas

### 1. **Instalación de Dependencias**
```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
```
- Se instalaron todas las dependencias de Laravel y paquetes requeridos
- Total: 81 paquetes instalados

### 2. **Configuración del Entorno**

**Archivo .env creado con:**
```bash
cp .env.example .env
php artisan key:generate
```

**Configuración de Base de Datos:**
- Tipo: SQLite (para desarrollo y pruebas)
- Ubicación: `database/database.sqlite`

### 3. **Ejecución de Migraciones**

```bash
php artisan migrate:fresh --seed
```

**Resultado: 22 Migraciones Ejecutadas**

| # | Migración | Descripción |
|---|-----------|-------------|
| 1 | `0001_01_01_000000_create_users_table` | Tabla de usuarios |
| 2 | `0001_01_01_000001_create_cache_table` | Sistema de caché |
| 3 | `0001_01_01_000002_create_jobs_table` | Cola de trabajos |
| 4 | `2025_03_22_150535_add_activo_to_users_table` | Campo activo en usuarios |
| 5 | `2025_03_31_005817_create_permission_tables` | Sistema de permisos (Spatie) |
| 6 | `2025_04_08_032354_create_productos_table` | Tabla de productos |
| 7 | `2025_04_19_155802_create_pedidos_table` | Tabla de pedidos |
| 8 | `2025_04_19_155822_create_pedido_detalles_table` | Detalles de pedidos |
| 9 | `2025_10_29_203531_create_categorias_table` | Tabla de categorías |
| 10 | `2025_11_04_210449_add_categoria_id_to_productos_table` | Relación producto-categoría |
| 11 | `2025_11_09_130645_create_catalogos_table` | Tabla de catálogos |
| 12 | `2025_11_09_154952_add_catalogo_id_to_productos_table` | Relación producto-catálogo |
| 13 | `2025_11_10_220623_create_carritos_table` | Carrito de compras |
| 14 | `2025_11_11_203630_add_contenido_to_pedidos_table` | Contenido de pedidos |
| 15 | `2025_11_12_000000_add_telefono_direccion_to_users_table` | Datos de contacto |
| 16 | `2025_11_12_000001_add_descuento_to_productos_table` | Sistema de descuentos |
| 17 | `2025_11_12_024805_add_ventas_to_productos_table` | Contador de ventas |
| 18 | `2025_11_12_210044_alter_pedido_detalles_precio_column` | Ajuste tipo de dato precio |
| 19 | `2025_11_12_210103_alter_pedidos_total_column` | Ajuste tipo de dato total |
| 20 | `2025_11_12_210126_alter_productos_precio_column` | Ajuste tipo de dato precio |
| 21 | `2025_11_21_192900_add_descuento_to_productos_table` | Descuento adicional |
| 22 | `2025_11_21_195500_create_pedido_referencias_table` | Referencias de pedidos |

---

## 🌱 Datos Iniciales (Seeders)

### Seeder Ejecutado: `RolesAndPermissionsSeeder`

**Datos Creados:**

### 👥 Usuarios (2)
| Nombre | Email | Contraseña | Rol |
|--------|-------|------------|-----|
| Admin | admin@prueba.com | admin123456 | admin |
| Cliente | cliente@prueba.com | cliente123456 | cliente |

### 🔐 Roles (2)
1. **admin** - Administrador del sistema
2. **cliente** - Cliente regular

### 🔑 Permisos (26)

#### Permisos de Admin:
- **Usuarios**: user-list, user-create, user-edit, user-delete, user-activate
- **Roles**: rol-list, rol-create, rol-edit, rol-delete
- **Productos**: producto-list, producto-create, producto-edit, producto-delete
- **Pedidos**: pedido-list, pedido-anulate
- **Categorías**: categoria-list, categoria-create, categoria-edit, categoria-delete
- **Catálogos**: catalogo-list, catalogo-create, catalogo-edit, catalogo-delete

#### Permisos de Cliente:
- **Pedidos**: pedido-view, pedido-cancel
- **Perfil**: perfil

---

## 📊 Estadísticas de la Base de Datos

```
Database: SQLite 3.45.1
Connection: sqlite
Path: database/database.sqlite
Tables: 21
Size: 156.00 KB
```

### Tablas Creadas:
- cache, cache_locks
- carritos
- catalogos
- categorias
- failed_jobs, job_batches, jobs
- migrations
- model_has_permissions, model_has_roles
- pedido_detalles, pedido_referencias, pedidos
- permissions
- productos
- role_has_permissions, roles
- sessions
- users

---

## 🚀 Siguiente Paso

Para iniciar el servidor de desarrollo:

```bash
php artisan serve
```

El servidor estará disponible en: `http://127.0.0.1:8000`

---

## 📝 Notas Importantes

1. **Credenciales de Prueba**: Las contraseñas de los usuarios de prueba son para desarrollo únicamente
2. **Base de Datos**: Se utiliza SQLite para facilitar el desarrollo. Para producción, configurar MySQL/MariaDB
3. **Permisos**: El sistema de permisos está implementado con Spatie Laravel Permission
4. **Seeders**: Los seeders crean datos mínimos necesarios para comenzar a trabajar

---

## 🔧 Comandos Útiles

### Ver estado de las migraciones:
```bash
php artisan migrate:status
```

### Rollback de la última migración:
```bash
php artisan migrate:rollback
```

### Refrescar la base de datos:
```bash
php artisan migrate:fresh --seed
```

### Ver información de la base de datos:
```bash
php artisan db:show
```

---

**Fecha de Configuración**: 22 de Noviembre, 2025
**Estado**: ✅ Configuración Completa y Funcional
