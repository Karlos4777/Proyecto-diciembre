<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Pedido</title>
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
            background: linear-gradient(90deg, #4A2F1E 0%, #1A1A1A 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 30px;
        }
        .content {
            padding: 30px;
        }
        .pedido-info {
            background-color: #efe3d8;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .producto {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .producto:last-child {
            border-bottom: none;
        }
        .total {
            background-color: #6F4E37;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            border-radius: 6px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 Nuevo Pedido Recibido</h1>
        </div>

        <div class="alert">
            <strong>¡Atención!</strong> Se ha recibido un nuevo pedido que requiere procesamiento.
        </div>

        <div class="content">
            <div class="pedido-info">
                <h2>Información del Pedido</h2>
                <div class="info-row">
                    <strong>Número de Pedido:</strong>
                    <span>#{{ $pedido->id }}</span>
                </div>
                <div class="info-row">
                    <strong>Cliente:</strong>
                    <span>{{ $pedido->user->name }} ({{ $pedido->user->email }})</span>
                </div>
                <div class="info-row">
                    <strong>Fecha:</strong>
                    <span>{{ $pedido->fecha }}</span>
                </div>
                <div class="info-row">
                    <strong>Estado:</strong>
                    <span>{{ ucfirst($pedido->estado) }}</span>
                </div>
            </div>

            <h3>Productos del Pedido:</h3>
            @foreach($pedido->contenido as $item)
                @php
                    $producto = \App\Models\Producto::find($item['producto_id']);
                @endphp
                @if($producto)
                <div class="producto">
                    <div><strong>{{ $producto->nombre }}</strong></div>
                    <div>Código: {{ $producto->codigo }}</div>
                    <div>Cantidad: {{ $item['cantidad'] }} x ${{ number_format($item['precio'], 2) }} = <strong>${{ number_format($item['cantidad'] * $item['precio'], 2) }}</strong></div>
                </div>
                @endif
            @endforeach

            <div class="total">
                Total del Pedido: ${{ number_format($pedido->total, 2) }}
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/pedidos/' . $pedido->id) }}" class="btn">Ver Pedido en el Sistema</a>
            </div>
        </div>

        <div class="footer">
            <p>© 2025 DiscZone - Panel de Administración</p>
            <p>Este es un correo automático del sistema.</p>
        </div>
    </div>
</body>
</html>
