<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos</title>
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
    <h1>Reporte de Productos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Desc %</th>
                <th>Cantidad</th>
                <th>Categoría</th>
                <th>Catálogo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->codigo }}</td>
                <td>{{ $p->nombre }}</td>
                <td class="text-end">{{ number_format($p->precio,2) }}</td>
                <td class="text-end">{{ $p->descuento ?? 0 }}</td>
                <td class="text-end">{{ $p->cantidad }}</td>
                <td>{{ $p->categoria->nombre ?? '' }}</td>
                <td>{{ $p->catalogo->nombre ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top:10px;">Total productos: {{ $productos->count() }}</p>
    <small>Generado: {{ now()->format('d/m/Y H:i:s') }}</small>
</body>
</html>