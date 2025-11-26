@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Dashboard</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(Session::has('mensaje'))
                            <div class="alert alert-info alert-dismissible fade show mt-2">
                                {{Session::get('mensaje')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                            </div>
                        @endif

                        <form method="GET" action="{{ route('dashboard') }}" class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Desde</label>
                                <input type="date" class="form-control" name="from" value="{{ $filters['from'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Hasta</label>
                                <input type="date" class="form-control" name="to" value="{{ $filters['to'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Categoría</label>
                                <select class="form-select" name="categoria_id">
                                    <option value="">Todas</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}" {{ ($filters['categoria_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Catálogo</label>
                                <select class="form-select" name="catalogo_id">
                                    <option value="">Todos</option>
                                    @foreach($catalogos as $ctlg)
                                        <option value="{{ $ctlg->id }}" {{ ($filters['catalogo_id'] ?? '') == $ctlg->id ? 'selected' : '' }}>
                                            {{ $ctlg->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-primary">Aplicar filtros</button>
                            </div>
                        </form>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-muted">Ventas totales</h6>
                                        <h3 class="fw-bold text-success">${{ number_format($metrics['ventasTotal'] ?? 0, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-muted">Pedidos</h6>
                                        <h3 class="fw-bold">{{ $metrics['pedidosCount'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-muted">Unidades vendidas</h6>
                                        <h3 class="fw-bold">{{ $metrics['unidadesVendidas'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="text-muted">Ticket promedio</h6>
                                        <h3 class="fw-bold">${{ number_format($metrics['ticketPromedio'] ?? 0, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header"><strong>Tendencia de ventas (últimos 30 días)</strong></div>
                                    <div class="card-body">
                                        <canvas id="ventasChart" style="max-height:300px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header"><strong>Top productos</strong></div>
                                    <div class="card-body">
                                        @if(($topProductos ?? collect())->isEmpty())
                                            <p class="text-muted">Sin datos.</p>
                                        @else
                                            <ul class="list-group">
                                                @foreach($topProductos as $p)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>{{ $p->nombre }}</span>
                                                        <span class="badge bg-primary">{{ $ventasPorProducto[$p->id] ?? 0 }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header"><strong>Distribución por categoría</strong></div>
                                    <div class="card-body">
                                        <canvas id="categoriaChart" style="max-height:250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header"><strong>Carritos abandonados</strong></div>
                                    <div class="card-body">
                                        @forelse($carritosAbandonados as $cart)
                                            <div class="mb-2 pb-2 border-bottom">
                                                <small class="text-muted">{{ $cart->user->name ?? 'Usuario' }}</small>
                                                <div class="text-muted" style="font-size:0.85rem;">
                                                    {{ count($cart->contenido ?? []) }} producto(s) - 
                                                    Última act: {{ $cart->updated_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">No hay carritos abandonados.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header"><strong>Stock crítico</strong></div>
                                    <div class="card-body">
                                        <h6 class="mb-2">Pocas unidades</h6>
                                        @forelse($stockCritico as $p)
                                            <div class="d-flex justify-content-between">
                                                <span>{{ $p->nombre }}</span>
                                                <span class="badge bg-warning text-dark">{{ $p->cantidad }}</span>
                                            </div>
                                        @empty
                                            <p class="text-muted">Sin productos con pocas unidades.</p>
                                        @endforelse
                                        <hr>
                                        <h6 class="mb-2">Agotados</h6>
                                        @forelse($agotados as $p)
                                            <div class="d-flex justify-content-between">
                                                <span>{{ $p->nombre }}</span>
                                                <span class="badge bg-danger">Agotado</span>
                                            </div>
                                        @empty
                                            <p class="text-muted">Sin productos agotados.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.getElementById('mnuDashboard').classList.add('active');

    // Gráfica de ventas por día
    const ventasData = @json($ventasPorDia);
    const ctx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ventasData.map(d => d.fecha),
            datasets: [{
                label: 'Ventas ($)',
                data: ventasData.map(d => d.total),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toFixed(2);
                        }
                    }
                }
            }
        }
    });

    // Gráfica de distribución por categoría
    const categoriaData = @json($ventasPorCategoria);
    const ctxCat = document.getElementById('categoriaChart').getContext('2d');
    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: Object.keys(categoriaData),
            datasets: [{
                data: Object.values(categoriaData),
                backgroundColor: [
                    '#0d6efd', '#6f42c1', '#d63384', '#fd7e14', 
                    '#ffc107', '#20c997', '#0dcaf0', '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 15, padding: 10 }
                }
            }
        }
    });
</script>
@endpush