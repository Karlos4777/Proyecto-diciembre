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
                        <h3 class="card-title">Pedidos</h3>
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
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-pedidos">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;" class="text-center">Opciones</th>
                                        <th style="width: 40px;" class="d-none d-sm-table-cell">ID</th>
                                        <th class="d-none d-md-table-cell">Fecha</th>
                                        <th class="d-none d-lg-table-cell">Usuario</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center d-none d-md-table-cell">Estado</th>
                                        <th style="width: 80px;" class="text-center">Detalles</th>
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
                                                <button class="btn btn-sm btn-primary btn-toggle" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#detalles-{{ $reg->id }}" aria-expanded="false" aria-controls="detalles-{{ $reg->id }}" title="Ver detalles">
                                                    <i class="bi bi-chevron-down toggle-icon"></i>
                                                    <span class="ms-1 d-none d-md-inline">Ver</span>
                                                </button>
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
                                                                <strong>#{{$reg->id}}</strong>
                                                            </div>
                                                            <div class="col-6 text-end">
                                                                <small class="text-muted">Fecha:</small><br>
                                                                <small>{{$reg->created_at->format('d/m/Y')}}</small>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">Usuario:</small><br>
                                                                <small>{{$reg->user->name}}</small>
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
                                                <table class="table table-sm table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Producto</th>
                                                            <th class="d-none d-sm-table-cell">Imagen</th>
                                                            <th class="text-center">Cant.</th>
                                                            <th class="text-end d-none d-md-table-cell">P. Unit.</th>
                                                            <th class="text-end">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($reg->detalles && count($reg->detalles) > 0)
                                                            @foreach($reg->detalles as $detalle)
                                                            <tr>
                                                                <td><small>{{ $detalle->producto->nombre }}</small></td>
                                                                <td class="d-none d-sm-table-cell">
                                                                    <img src="{{ asset('uploads/productos/' . $detalle->producto->imagen ) }}"
                                                                        class="detalle-img"
                                                                        alt="{{ $detalle->producto->nombre}}"
                                                                        style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                                                </td>
                                                                <td class="text-center">{{ $detalle->cantidad}}</td>
                                                                <td class="text-end d-none d-md-table-cell"><small>${{ number_format($detalle->precio, 2) }}</small></td>
                                                                <td class="text-end"><small>${{ number_format($detalle->cantidad * $detalle->precio, 2) }}</small></td>
                                                            </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="5" class="text-center text-muted">
                                                                    No hay detalles para este pedido
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
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
                                <button type="button" class="btn btn-secondary me-md-2"
                                    onclick="window.location.href='{{route('web.index')}}'">Cancelar</button>
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