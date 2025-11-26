<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Pedidos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #555; padding: 4px 6px; }
        th { background: #eee; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <h1>Reporte de Pedidos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Creado</th>
                <th>Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->user->name ?? '' }}</td>
                <td>{{ $p->estado }}</td>
                <td class="text-end">{{ number_format($p->total,2) }}</td>
                <td>{{ $p->created_at->format('d/m/Y') }}</td>
                <td class="text-end">{{ $p->lineas->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top:10px;">Total pedidos: {{ $pedidos->count() }}</p>
    <small>Generado: {{ now()->format('d/m/Y H:i:s') }}</small>
</body>
</html>