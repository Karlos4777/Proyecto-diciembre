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
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Pedidos</h3>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('reportes.pedidos.csv') }}" class="btn btn-outline-primary" title="Exportar CSV">
                                    <i class="bi bi-filetype-csv me-1"></i> CSV
                                </a>
                                <a href="{{ route('reportes.pedidos.pdf') }}" class="btn btn-outline-danger" title="Exportar PDF">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                                </a>
                                <a href="{{ route('reportes.pedidos.excel') }}" class="btn btn-outline-success" title="Exportar Excel">
                                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div>
                            <form action="{{route('productos.index')}}" method="get">
                                <div class="input-group input-group-responsive">
                                    <input name="texto" type="text" class="form-control" value="{{$texto}}"
                                        placeholder="Ingrese texto a buscar">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i>
                                            <span class="d-none d-sm-inline ms-1">Buscar</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if(Session::has('mensaje'))
                        <div class="alert alert-info alert-dismissible fade show mt-2">
                            {{Session::get('mensaje')}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                        </div>
                        @endif
                        <!-- Page-specific inline styles moved to `site-fixes.css` -->

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-pedidos">
                                <thead>
                                    <tr>
                                        <th class="th-w-60 text-center">Opciones</th>
                                        <th class="th-w-40 d-none d-sm-table-cell">ID</th>
                                        <th class="d-none d-md-table-cell">Fecha</th>
                                        <th class="d-none d-lg-table-cell">Usuario</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center d-none d-md-table-cell">Estado</th>
                                        <th class="th-w-120 text-center">Referencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($registros && count($registros) > 0)
                                        @foreach($registros as $reg)
                                        <tr class="align-middle">
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-estado-{{$reg->id}}" title="Cambiar estado">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </td>
                                            <td class="d-none d-sm-table-cell">{{$reg->id}}</td>
                                            <td class="d-none d-md-table-cell">
                                                <small>{{$reg->created_at->format('d/m/Y')}}</small>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <small>{{$reg->user->name}}</small>
                                            </td>
                                            <td class="text-center fw-bold">${{ number_format($reg->total, 2) }}</td>
                                            <td class="text-center d-none d-md-table-cell">
                                                @php
                                                    $colores = [
                                                        'pendiente' => 'bg-warning',
                                                        'enviado' => 'bg-success',
                                                        'anulado' => 'bg-danger',
                                                        'cancelado' => 'bg-secondary',
                                                    ];
                                                @endphp
                                                <span class="badge {{ $colores[$reg->estado] ?? 'bg-dark' }}">
                                                    {{ ucfirst($reg->estado) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <!-- Botón referencia: abre un pequeño collapse para subir archivo -->
                                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#referencia-{{ $reg->id }}" aria-expanded="false">
                                                    <i class="bi bi-paperclip"></i> Ver detalles por recibo
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="collapse referencia-row" id="referencia-{{ $reg->id }}">
                                            <td colspan="7">
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label class="form-label mb-1">Archivos subidos</label>
                                                            @if($reg->referencias && $reg->referencias->count() > 0)
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach($reg->referencias as $ref)
                                                                        @php $ext = pathinfo($ref->filename, PATHINFO_EXTENSION); @endphp
                                                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif']))
                                                                            <a href="{{ asset('storage/' . $ref->path) }}" target="_blank" title="{{ $ref->filename }}">
                                                                                <img src="{{ asset('storage/' . $ref->path) }}" alt="{{ $ref->filename }}" class="pedido-ref-thumb" style="max-width:120px;max-height:120px;object-fit:cover;border:1px solid #e9ecef;padding:3px;border-radius:4px;" />
                                                                            </a>
                                                                        @else
                                                                            <div class="file-link badge bg-light border p-2">
                                                                                <a href="{{ asset('storage/' . $ref->path) }}" target="_blank">{{ $ref->filename }}</a>
                                                                                <div class="small text-muted">{{ number_format($ref->size/1024,1) }} KB</div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div class="small text-muted">Aún no hay archivos subidos para este pedido.</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Mobile card view for details -->
                                        <tr class="d-md-none">
                                            <td colspan="5">
                                                <div class="card card-sm">
                                                    <div class="card-body p-2">
                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <small class="text-muted">Pedido:</small><br>
                                                                <strong>#{{ $reg->id }}</strong>
                                                            </div>
                                                            <div class="col-6 text-end">
                                                                <small class="text-muted">Fecha:</small><br>
                                                                <small>{{ $reg->created_at->format('d/m/Y') }}</small>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">Usuario:</small><br>
                                                                <small>{{ $reg->user->name }}</small>
                                                            </div>
                                                            <div class="col-6 text-end">
                                                                <small class="text-muted">Estado:</small><br>
                                                                <span class="badge {{ $colores[$reg->estado] ?? 'bg-dark' }} mt-1">
                                                                    {{ ucfirst($reg->estado) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="collapse detalles-row" id="detalles-{{ $reg->id }}">
                                            <td colspan="7">
                                                <div class="p-3">
                                                    <!-- Menú desplegable Ver más para productos -->
                                                    <div class="mb-3">
                                                        <div class="btn-group w-100" role="group">
                                                            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#productos-detalle-{{ $reg->id }}" aria-expanded="false">
                                                                <i class="bi bi-chevron-down"></i> Ver más
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Contenido desplegable -->
                                                        <div class="collapse mt-2" id="productos-detalle-{{ $reg->id }}">
                                                            <div class="card card-body">
                                                                @if($reg->detalles && count($reg->detalles) > 0)
                                                                    @foreach($reg->detalles as $detalle)
                                                                        <div class="row g-2 mb-3 pb-3 border-bottom align-items-center">
                                                                            <!-- Imagen -->
                                                                            <div class="col-auto">
                                                                                    @if($detalle->producto && $detalle->producto->imagen)
                                                                                        <img src="{{ asset('uploads/productos/' . $detalle->producto->imagen) }}" 
                                                                                             class="pedido-thumb" 
                                                                                             alt="{{ $detalle->producto->nombre ?? 'Producto' }}">
                                                                                    @else
                                                                                        <div class="pedido-thumb-placeholder">
                                                                                            <i class="bi bi-image text-muted"></i>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            
                                                                            <!-- Nombre -->
                                                                            <div class="col">
                                                                                <strong>
                                                                                    @if($detalle->producto)
                                                                                        <a href="{{ route('web.show', $detalle->producto->id) }}" class="text-decoration-none">{{ $detalle->producto->nombre }}</a>
                                                                                    @else
                                                                                        Producto eliminado
                                                                                    @endif
                                                                                </strong>
                                                                            </div>
                                                                            
                                                                            <!-- Cantidad -->
                                                                            <div class="col-auto text-center">
                                                                                <small class="text-muted d-block">Cantidad</small>
                                                                                <strong>{{ $detalle->cantidad }}</strong>
                                                                            </div>
                                                                            
                                                                            <!-- Precio Unitario -->
                                                                            <div class="col-auto text-end">
                                                                                <small class="text-muted d-block">Precio Unit.</small>
                                                                                <strong>${{ number_format($detalle->precio, 2) }}</strong>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="alert alert-info mb-0">
                                                                        No hay detalles para este pedido
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Información adicional del pedido -->
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small class="text-muted">Cliente:</small><br>
                                                            <strong>{{ $reg->user->name ?? 'N/A' }}</strong><br>
                                                            <small class="text-muted">Email:</small> <small>{{ $reg->user->email ?? 'N/A' }}</small><br>
                                                            <small class="text-muted mt-2 d-block">Teléfono:</small> <small>{{ $reg->user->telefono ?? 'N/A' }}</small>
                                                        </div>
                                                        <div class="col-md-6 text-end">
                                                            <small class="text-muted">Fecha:</small><br>
                                                            <strong>{{ $reg->created_at->format('d/m/Y H:i') }}</strong><br>
                                                            <small class="text-muted">Dirección:</small><br>
                                                            <small>{{ $reg->user->direccion ?? 'N/A' }}</small><br>
                                                            <small class="text-muted mt-2 d-block">Total:</small> <strong class="text-success">${{ number_format($reg->total,2) }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('pedido.state')
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No hay registros que coincidan con la búsqueda
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('web.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                        </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{$registros->appends(["texto"=>$texto])}}
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
@push('scripts')
    <script src="{{ asset('js/pedido.js') }}"></script>
@endpush
@endsection