@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Productos</h3>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('reportes.productos.csv') }}" class="btn btn-outline-primary" title="Exportar CSV">
                                    <i class="bi bi-filetype-csv me-1"></i> CSV
                                </a>
                                <a href="{{ route('reportes.productos.pdf') }}" class="btn btn-outline-danger" title="Exportar PDF">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                                </a>
                                <a href="{{ route('reportes.productos.excel') }}" class="btn btn-outline-success" title="Exportar Excel">
                                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <form action="{{route('productos.index')}}" method="get">
                                <div class="input-group">
                                    <input name="texto" type="text" class="form-control" value="{{$texto}}"
                                        placeholder="Ingrese texto a buscar">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Buscar</button>
                                        @can('producto-create')
                                        <a href="{{route('productos.create')}}" class="btn btn-primary"> Nuevo</a>
                                        @endcan
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
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="th-w-150">Opciones</th>
                                        <th class="th-w-20">ID</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Descuento</th>
                                        <th>Cantidad</th>
                                        <th>Categoría</th>
                                        <th>Catálogo</th>
                                        <th>Imagen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($registros)<=0)
                                        <tr>
                                            <td colspan="10">No hay registros que coincidan con la búsqueda</td>
                                        </tr>
                                    @else
                                        @foreach($registros as $reg)
                                            <tr class="align-middle">
                                                <td>
                                                    @can('producto-edit')
                                                    <a href="{{route('productos.edit', $reg->id)}}" class="btn btn-info btn-sm"><i class="bi bi-pencil-fill"></i></a>&nbsp;
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal-descuento-{{ $reg->id }}"><i class="bi bi-tag-fill"></i></button>&nbsp;
                                                    @endcan
                                                    @can('producto-delete')
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-eliminar-{{$reg->id}}"><i class="bi bi-trash-fill"></i></button>
                                                    @endcan
                                                </td>
                                                <td>{{$reg->id}}</td>
                                                <td>{{$reg->codigo}}</td>
                                                <td>{{$reg->nombre}}</td>
                                                <td>
                                                    @if(!empty($reg->descuento) && $reg->descuento > 0)
                                                        <small class="text-muted text-decoration-line-through me-2">${{ number_format($reg->precio,2) }}</small>
                                                        <span class="fw-bold text-success">${{ number_format($reg->precio_con_descuento ?? $reg->precio,2) }}</span>
                                                    @else
                                                        ${{ number_format($reg->precio,2) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($reg->descuento) && $reg->descuento > 0)
                                                        <span class="badge bg-warning text-dark">-{{ $reg->descuento }}%</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{$reg->cantidad}}</td>
                                                <td>
                                                    @if($reg->categoria)
                                                        <span class="badge bg-primary">{{ $reg->categoria->nombre }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">Sin Categoría</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($reg->catalogo)
                                                        <span class="badge bg-success">{{ $reg->catalogo->nombre }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">Sin Catálogo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($reg->imagen)
                                                        <img src="{{ asset('uploads/productos/' . $reg->imagen) }}" alt="{{ $reg->nombre }}" class="img-max-150">
                                                    @else
                                                        <span class="text-muted">Sin imagen</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @can('producto-delete')
                                                @include('producto.delete')
                                            @endcan
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        {{$registros->appends(["texto"=>$texto])}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@foreach($registros as $reg)
        @can('producto-edit')
        <!-- Modal: editar descuento rápido -->
        <div class="modal fade" id="modal-descuento-{{ $reg->id }}" tabindex="-1" aria-labelledby="modalDescuentoLabel{{ $reg->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDescuentoLabel{{ $reg->id }}">Editar descuento - {{ $reg->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('productos.promocion.assign') }}">
                        @csrf
                        <div class="modal-body">
                                <input type="hidden" name="producto_id" value="{{ $reg->id }}">
                                <div class="mb-3">
                                        <label for="descuento_{{ $reg->id }}" class="form-label">Descuento (%)</label>
                                        <input id="descuento_{{ $reg->id }}" name="descuento" type="number" min="0" max="100" step="1" class="form-control" value="{{ old('descuento', $reg->descuento ?? 0) }}">
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
@endforeach
