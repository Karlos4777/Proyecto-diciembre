# 📋 Flujo Completo de Compra - Sistema de Pedidos

## 🔄 Resumen del Proceso

El sistema de compra funciona con el siguiente flujo:

```
Carrito → Formulario Pedido → Guardar Pedido → Index (Éxito) → Mis Pedidos
```

---

## 1️⃣ **Agregar Productos al Carrito**

### Ruta: `POST /carrito/agregar`
- **Controlador**: `CarritoController@agregar`
- **Acción**: Guarda productos en la tabla `carritos`
- **Redirección**: Vuelve a la página anterior con mensaje

```php
// CarritoController.php - Línea ~35
$contenido[$producto->id] = [
    'nombre' => $producto->nombre,
    'codigo' => $producto->codigo,
    'precio' => $producto->precio,
    'cantidad' => 1,
    'imagen' => $producto->imagen,
];
```

---

## 2️⃣ **Ver el Carrito**

### Ruta: `GET /carrito`
- **Controlador**: `CarritoController@mostrar`
- **Vista**: `web/pedido.blade.php`
- **Muestra**: 
  - Todos los productos en el carrito
  - Botones para aumentar/disminuir cantidades
  - Total a pagar
  - Botón "Proceder al pedido"

---

## 3️⃣ **Abrir Formulario de Pedido**

### Ruta: `GET /pedido/formulario`
- **Controlador**: `PedidoController@formulario`
- **Vista**: `web/formulario_pedido.blade.php`

### Validaciones:
- Verifica que el carrito no esté vacío
- Redirige al carrito si está vacío

### El Formulario Incluye:
✅ Resumen del carrito (cantidad, precio, subtotal)  
✅ Campo: Nombre completo (pre-llenado con `auth()->user()->name`)  
✅ Campo: Email (pre-llenado con `auth()->user()->email`)  
✅ Campo: Teléfono  
✅ Campo: Dirección  
✅ Campo: Método de pago (tarjeta, nequi, efectivo)  
✅ Total a pagar (cálculo en tiempo real)  
✅ Validación de errores en frontend

---

## 4️⃣ **Procesar la Compra**

### Ruta: `POST /pedido/realizar`
- **Controlador**: `PedidoController@realizar`

### Proceso Paso a Paso:

#### A. Validación
```php
$request->validate([
    'nombre'       => 'required',
    'email'        => 'required|email',
    'telefono'     => 'required',
    'direccion'    => 'required',
    'metodo_pago'  => 'required',
]);
```

#### B. Transacción en Base de Datos (DB::beginTransaction)

**Paso 1**: Obtener el carrito del usuario
```php
$registro = Carrito::firstOrCreate(
    ['user_id' => auth()->id()],
    ['contenido' => []]
);
$carrito = $registro->contenido ?? [];
```

**Paso 2**: Calcular el total
```php
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
```

**Paso 3**: Crear el pedido en tabla `pedidos`
```php
$pedido = Pedido::create([
    'user_id'  => auth()->id(),
    'total'    => $total,
    'estado'   => 'pendiente',
    'fecha'    => now()->toDateString(),
]);
```

**Paso 4**: Crear detalles en tabla `pedido_detalles`
```php
foreach ($carrito as $productoId => $item) {
    PedidoDetalle::create([
        'pedido_id'   => $pedido->id,
        'producto_id' => $productoId,
        'cantidad'    => $item['cantidad'],
        'precio'      => $item['precio'],
    ]);
}
```

**Paso 5**: Vaciar el carrito
```php
$registro->contenido = [];
$registro->save();
```

**Paso 6**: Confirmar transacción
```php
DB::commit();
```

#### C. Redirección con Mensaje de Éxito
```php
return redirect()->route('web.index')
    ->with('success', '¡Compra exitosa! Tu pedido #' . $pedido->id . ' ha sido registrado.');
```

---

## 5️⃣ **Ver Mensaje de Éxito en Index**

### Ruta: `GET /`
- **Controlador**: `WebController@index`
- **Vista**: `web/index.blade.php`

### Mensaje Mostrado:
```
✅ ¡Compra exitosa! 🎉
Tu pedido #123 ha sido registrado.

[Ver mis pedidos] [Continuar comprando]
```

**Características del Mensaje:**
- ✅ Se muestra en un alert verde prominente
- ✅ Auto-cierra después de 5 segundos
- ✅ Incluye 2 botones de acción rápida
- ✅ Puede cerrarse manualmente con la X

---

## 6️⃣ **Ver los Pedidos en el Perfil**

### Ruta: `GET /perfil/pedidos`
- **Controlador**: `PedidoController@index`
- **Vista**: `web/mis_pedidos.blade.php` (para clientes)

### Funcionalidades:
✅ Muestra SOLO los pedidos del usuario logueado  
✅ Ordenados por más reciente primero  
✅ Para cada pedido muestra:
   - ID del pedido
   - Fecha y hora
   - Estado (pendiente, enviado, cancelado, anulado)
   - Tabla con detalles:
     - Imagen del producto
     - Nombre del producto
     - Cantidad
     - Precio unitario
     - Subtotal
   - Resumen con total
   - Botón para cancelar si está pendiente

✅ Paginación de 10 pedidos por página

---

## 7️⃣ **Estados de los Pedidos**

| Estado | Color | Significado |
|--------|-------|-------------|
| **pendiente** | 🟨 Amarillo | El pedido está registrado pero sin procesar |
| **enviado** | 🟩 Verde | El pedido ha sido enviado |
| **cancelado** | 🟥 Rojo | El usuario canceló el pedido |
| **anulado** | ⬜ Gris | El admin anuló el pedido |

---

## 📊 Estructura de Tablas

### Tabla: `pedidos`
```
id (int, PK)
user_id (int, FK → users)
fecha (date)
total (decimal 10,2)
estado (string 20) - default: 'pendiente'
detalles (text, nullable)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: `pedido_detalles`
```
id (int, PK)
pedido_id (int, FK → pedidos)
producto_id (int, FK → productos)
cantidad (int)
precio (decimal 10,2)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: `carritos`
```
id (int, PK)
user_id (int, FK → users)
contenido (json) - Almacena los productos:
  {
    "producto_id": {
      "nombre": "...",
      "codigo": "...",
      "precio": 100.00,
      "cantidad": 2,
      "imagen": "..."
    }
  }
created_at (timestamp)
updated_at (timestamp)
```

---

## 🔐 Permisos Requeridos

### Para Ver Pedidos:
- **Admin**: Permiso `pedido-list` → Ve TODOS los pedidos
- **Cliente**: Permiso `pedido-view` → Ve solo SUS pedidos

---

## 🛡️ Manejo de Errores

### Si el carrito está vacío:
```
❌ "El carrito está vacío." → Redirige a /carrito
```

### Si hay error al procesar:
```
❌ "Hubo un error al procesar el pedido: ..."
→ Vuelve al formulario sin perder datos (gracias a old())
```

### Si la validación falla:
```
❌ Muestra los errores en rojo debajo de cada campo
✅ Los datos se conservan en los inputs (old())
```

---

## 🚀 Para Probar el Sistema:

1. **Inicia sesión** como cliente (email: `cliente@prueba.com`, pwd: `cliente123456`)
2. **Busca productos** en el index
3. **Agrega 2-3 productos** al carrito
4. **Ve al carrito** desde el menú
5. **Haz clic en "Proceder al pedido"**
6. **Completa el formulario** y da clic en "Confirmar compra"
7. **Verás el mensaje de éxito** en el index
8. **Haz clic en "Ver mis pedidos"** para confirmarlo
9. **El carrito está vacío** si vuelves a verlo

---

## 📝 Archivos Modificados

1. ✅ `app/Http/Controllers/PedidoController.php` - Métodos `index()` y `realizar()`
2. ✅ `app/Models/User.php` - Agregada relación `pedidos()`
3. ✅ `resources/views/web/formulario_pedido.blade.php` - Diseño mejorado
4. ✅ `resources/views/web/index.blade.php` - Mensaje de éxito mejorado
5. ✅ `resources/views/web/mis_pedidos.blade.php` - Vista nueva para pedidos del cliente

---

## ⚠️ Notas Importantes

- El `user_id` se obtiene automáticamente de `auth()->id()`
- El estado inicial de un pedido es siempre **"pendiente"**
- El carrito se vacía SOLO después de una compra exitosa
- Los detalles del pedido se guardan en la tabla `pedido_detalles` para historico
- Las imágenes se muestran desde `uploads/productos/`
- El mensaje de éxito auto-cierra después de 5 segundos
