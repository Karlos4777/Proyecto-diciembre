<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pedidos</title>
    <style>
        @page { margin: 80px 40px 60px 40px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        header { position: fixed; top: -60px; left: 0; right: 0; height: 60px; }
        footer { position: fixed; bottom: -40px; left: 0; right: 0; height: 40px; text-align: right; color: #666; }
        .pagenum:before { content: counter(page); }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand img { height: 28px; }
        .brand .title { font-size: 16px; font-weight: 700; color: #0d6efd; }
        .meta { text-align: right; font-size: 11px; color: #666; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead th { background: #e7f1ff; color: #0b5ed7; border-bottom: 1px solid #b6d4fe; padding: 8px; font-weight: 600; }
        tbody td { border-bottom: 1px solid #eee; padding: 7px; }
        tbody tr:nth-child(odd) { background: #fcfcfd; }
        tfoot td { border-top: 2px solid #b6d4fe; padding: 8px; font-weight: 700; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <header>
        <table style="width:100%;">
            <tr>
                <td style="width:60%;">
                    <div class="brand">
                        @php $logo = public_path('assets/img/nav-logo-img.png'); @endphp
                        @if(file_exists($logo))
                            <img src="{{ $logo }}" alt="Logo">
                        @endif
                        <div class="title">Reporte de Pedidos</div>
                    </div>
                </td>
                <td class="meta" style="width:40%;">
                    Fecha: {{ now()->format('d/m/Y H:i') }}<br>
                    Registros: {{ count($registros ?? []) }}
                </td>
            </tr>
        </table>
    </header>

    <footer>
        Página <span class="pagenum"></span>
    </footer>

    <main>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width:60px;">ID</th>
                    <th style="width:140px;">Fecha</th>
                    <th>Usuario</th>
                    <th style="width:110px;">Estado</th>
                    <th class="text-right" style="width:100px;">Total</th>
                    <th class="text-center" style="width:70px;">Items</th>
                </tr>
            </thead>
            <tbody>
                @php $totalGeneral = 0; @endphp
                @foreach($registros as $p)
                    @php $totalGeneral += (float)($p->total ?? 0); @endphp
                    <tr>
                        <td class="text-center">{{ $p->id }}</td>
                        <td>{{ optional($p->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->user->name ?? '—' }}</td>
                        <td>{{ ucfirst($p->estado) }}</td>
                        <td class="text-right">${{ number_format((float)($p->total ?? 0), 2) }}</td>
                        <td class="text-center">{{ optional($p->lineas)->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right">Total general</td>
                    <td class="text-right">${{ number_format($totalGeneral, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </main>
</body>
</html>
    <p style="margin-top:10px;">Total pedidos: {{ $pedidos->count() }}</p>
    <small>Generado: {{ now()->format('d/m/Y H:i:s') }}</small>
</body>
</html>