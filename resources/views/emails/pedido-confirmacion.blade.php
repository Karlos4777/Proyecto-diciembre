<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
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
            letter-spacing: 1px;
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
        .pedido-info h2 {
            margin-top: 0;
            color: #6F4E37;
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
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .producto:last-child {
            border-bottom: none;
        }
        .producto img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        .producto-info {
            flex: 1;
        }
        .producto-nombre {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .producto-precio {
            color: #6F4E37;
            font-weight: bold;
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
        .footer {
            background-color: #1A1A1A;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 14px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎵 DiscZone</h1>
            <p>¡Gracias por tu compra!</p>
        </div>

        <div class="content">
            <h2>Hola {{ $pedido->user->name }},</h2>
            <p>Tu pedido ha sido confirmado y está siendo procesado.</p>

            <div class="pedido-info">
                <h2>Información del Pedido</h2>
                <div class="info-row">
                    <strong>Número de Pedido:</strong>
                    <span>#{{ $pedido->id }}</span>
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

            <h3>Productos:</h3>
            @foreach($pedido->contenido as $item)
                @php
                    $producto = \App\Models\Producto::find($item['producto_id']);
                @endphp
                @if($producto)
                <div class="producto">
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                    <div class="producto-info">
                        <div class="producto-nombre">{{ $producto->nombre }}</div>
                        <div>Cantidad: {{ $item['cantidad'] }}</div>
                        <div class="producto-precio">${{ number_format($item['precio'], 2) }}</div>
                    </div>
                </div>
                @endif
            @endforeach

            <div class="total">
                Total: ${{ number_format($pedido->total, 2) }}
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/mis-pedidos') }}" class="btn">Ver Mis Pedidos</a>
            </div>

            <p style="margin-top: 30px; color: #666;">
                Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.
            </p>
        </div>

        <div class="footer">
            <p>© 2025 DiscZone - Tu zona musical favorita</p>
            <p>Este es un correo automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>
