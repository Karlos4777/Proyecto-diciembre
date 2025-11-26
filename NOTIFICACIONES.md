# 📧 Sistema de Notificaciones por Email - DiscZone

## Descripción

Sistema de notificaciones automáticas por email para la tienda de discos DiscZone. Incluye confirmaciones de pedidos y alertas de inventario.

## 🎯 Funcionalidades Implementadas

### 1. Confirmación de Pedido al Cliente
- **Cuándo:** Se envía automáticamente cuando un cliente completa una compra
- **Destinatario:** Email del cliente que realizó el pedido
- **Contenido:**
  - Número de pedido
  - Fecha y estado
  - Lista de productos con imágenes
  - Total del pedido
  - Enlace para ver sus pedidos

### 2. Notificación de Nuevo Pedido al Admin
- **Cuándo:** Se envía cuando se registra un nuevo pedido
- **Destinatario:** Todos los usuarios con rol "Admin"
- **Contenido:**
  - Información del cliente
  - Detalles del pedido
  - Lista de productos
  - Enlace directo al pedido en el panel

### 3. Alerta de Stock Bajo
- **Cuándo:** Se ejecuta manualmente o mediante cron job
- **Destinatario:** Administradores
- **Contenido:**
  - Productos agotados (cantidad = 0)
  - Productos con stock crítico (cantidad < 21)
  - Enlace a gestión de productos

## ⚙️ Configuración

### 1. Configurar Variables de Entorno

Edita tu archivo `.env`:

```env
# Para desarrollo (guarda emails en storage/logs/laravel.log)
MAIL_MAILER=log

# Para producción con Gmail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicación
MAIL_FROM_ADDRESS=noreply@disczone.com
MAIL_FROM_NAME="DiscZone"

# Email del admin principal
ADMIN_EMAIL=admin@disczone.com
```

### 2. Configurar Gmail (si usas Gmail)

1. Ir a [Configuración de Google](https://myaccount.google.com/)
2. Seguridad → Verificación en 2 pasos (debe estar activada)
3. Contraseñas de aplicaciones → Crear nueva
4. Copiar la contraseña de 16 caracteres
5. Usar esa contraseña en `MAIL_PASSWORD`

### 3. Otros Servicios de Email

**Mailtrap (para testing):**
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu-username
MAIL_PASSWORD=tu-password
```

**SendGrid:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu-api-key-de-sendgrid
```

## 🚀 Uso

### Emails Automáticos

Los emails de pedido se envían automáticamente cuando:
- Un cliente completa una compra
- No requiere configuración adicional

### Comando Manual para Alertas de Stock

Ejecutar manualmente:
```bash
php artisan productos:alertar-stock
```

### Configurar Cron Job (Recomendado)

Para ejecutar alertas diariamente a las 8 AM:

1. Abrir crontab (Linux/Mac):
```bash
crontab -e
```

2. Agregar línea:
```cron
0 8 * * * cd /ruta/a/tu/proyecto && php artisan productos:alertar-stock >> /dev/null 2>&1
```

3. En Windows (Programador de tareas):
   - Crear nueva tarea
   - Acción: `php.exe`
   - Argumentos: `c:\ruta\a\proyecto\artisan productos:alertar-stock`
   - Programar: Diariamente a las 8:00 AM

### Agregar al Scheduler de Laravel (Alternativa)

Editar `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Enviar alertas de stock todos los días a las 8 AM
    $schedule->command('productos:alertar-stock')
             ->dailyAt('08:00');
}
```

Luego agregar al crontab:
```cron
* * * * * cd /ruta/a/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

## 📝 Plantillas de Email

Las plantillas están en `resources/views/emails/`:

- `pedido-confirmacion.blade.php` - Confirmación al cliente
- `nuevo-pedido-admin.blade.php` - Notificación a admin
- `stock-bajo.blade.php` - Alerta de inventario

Puedes personalizarlas editando el HTML y CSS inline.

## 🧪 Testing

### Probar Emails en Desarrollo

1. Configurar `MAIL_MAILER=log` en `.env`
2. Realizar una compra o ejecutar comando
3. Ver emails en `storage/logs/laravel.log`

### Probar con Mailtrap

1. Crear cuenta en [Mailtrap.io](https://mailtrap.io)
2. Copiar credenciales SMTP
3. Configurar en `.env`
4. Realizar pruebas (emails se capturan en Mailtrap)

## 🔍 Troubleshooting

### Los emails no se envían

1. **Verificar configuración:**
```bash
php artisan config:clear
php artisan cache:clear
```

2. **Verificar logs:**
```bash
tail -f storage/logs/laravel.log
```

3. **Probar conexión SMTP:**
```bash
php artisan tinker
Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### Error "Failed to authenticate"

- Verificar que la contraseña sea correcta
- Si usas Gmail, debe ser contraseña de aplicación (no la normal)
- Verificar que 2FA esté activado en Gmail

### Los admins no reciben emails

- Verificar que existan usuarios con rol "Admin"
- Verificar que tengan email válido en la base de datos

```bash
php artisan tinker
User::role('Admin')->get();
```

## 📊 Próximas Mejoras

- [ ] Queue jobs para emails (evitar esperas)
- [ ] Reintentos automáticos si falla envío
- [ ] Plantillas más personalizables
- [ ] Email cuando cambia estado de pedido
- [ ] Newsletter para promociones

## 🤝 Soporte

Si tienes problemas:
1. Revisar logs: `storage/logs/laravel.log`
2. Verificar configuración `.env`
3. Consultar documentación de Laravel Mail

---

**Versión:** 1.0  
**Fecha:** Noviembre 2025  
**Autor:** DiscZone Development Team
