<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Stock Bajo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .alert-critical {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .producto {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        .producto:last-child {
            border-bottom: none;
        }
        .producto-nombre {
            font-weight: bold;
            color: #333;
        }
        .badge {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #1A1A1A;
        }
        .btn {
            display: inline-block;
            background-color: #6F4E37;
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            background-color: #1A1A1A;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .section {
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Alerta de Inventario</h1>
            <p>Productos con stock bajo o agotado</p>
        </div>

        <div class="content">
            @if(count($productosAgotados) > 0)
            <div class="section">
                <div class="alert-critical">
                    <strong>🚨 PRODUCTOS AGOTADOS</strong><br>
                    Los siguientes productos están sin stock y no pueden venderse:
                </div>

                @foreach($productosAgotados as $producto)
                <div class="producto">
                    <div>
                        <div class="producto-nombre">{{ $producto->nombre }}</div>
                        <div style="color: #666; font-size: 14px;">Código: {{ $producto->codigo }}</div>
                    </div>
                    <span class="badge badge-danger">AGOTADO</span>
                </div>
                @endforeach
            </div>
            @endif

            @if(count($productosCriticos) > 0)
            <div class="section">
                <div class="alert-warning">
                    <strong>⚡ STOCK CRÍTICO</strong><br>
                    Los siguientes productos tienen menos de 21 unidades en stock:
                </div>

                @foreach($productosCriticos as $producto)
                <div class="producto">
                    <div>
                        <div class="producto-nombre">{{ $producto->nombre }}</div>
                        <div style="color: #666; font-size: 14px;">Código: {{ $producto->codigo }}</div>
                    </div>
                    <span class="badge badge-warning">{{ $producto->cantidad }} unidades</span>
                </div>
                @endforeach
            </div>
            @endif

            <p style="margin-top: 30px; color: #666;">
                <strong>Acción requerida:</strong> Considera reabastecer estos productos para evitar pérdidas de ventas.
            </p>

            <div style="text-align: center;">
                <a href="{{ url('/productos') }}" class="btn">Ir a Gestión de Productos</a>
            </div>
        </div>

        <div class="footer">
            <p>© 2025 DiscZone - Sistema de Alertas de Inventario</p>
            <p>Este es un correo automático del sistema.</p>
        </div>
    </div>
</body>
</html>
