@extends('plantilla.app')

@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-7">
                    <div class="card-header">
                        <h3 class="card-title">Productos</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ isset($registro) ? route('productos.update', $registro->id) : route('productos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($registro))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <!-- Código -->
                                <div class="col-md-3 mb-3">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" class="form-control @error('codigo') is-invalid @enderror"
                                        id="codigo" name="codigo"
                                        value="{{ old('codigo', $registro->codigo ?? '') }}" required>
                                    @error('codigo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Nombre -->
                                <div class="col-md-3 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre" name="nombre"
                                        value="{{ old('nombre', $registro->nombre ?? '') }}" required>
                                    @error('nombre')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Precio -->
                                <div class="col-md-3 mb-3">
                                    <label for="precio" class="form-label">Precio</label>
                                    <input type="text" class="form-control @error('precio') is-invalid @enderror"
                                        id="precio" name="precio"
                                        value="{{ old('precio', $registro->precio ?? '') }}" required>
                                    @error('precio')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Categoría (CORREGIDO) -->
                                <div class="col-md-3 mb-3">
                                    <label for="categoria_id" class="form-label">Categoría</label>
                                    <select name="categoria_id" id="categoria_id" class="form-control" required>
                                        <option value="">Seleccione una categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}"
                                                {{ isset($registro) && $registro->categoria_id == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Descripción -->
                                <div class="col-md-6 mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea name="descripcion" class="form-control" id="descripcion" rows="4">{{ old('descripcion', $registro->descripcion ?? '') }}</textarea>
                                    @error('descripcion')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Imagen -->
                                <div class="col-md-3 mb-3">
                                    <label for="imagen" class="form-label">Imagen</label>
                                    <input type="file" class="form-control @error('imagen') is-invalid @enderror"
                                        id="imagen" name="imagen">
                                    @error('imagen')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    @if(isset($registro) && $registro->imagen)
                                        <div class="mt-2">
                                            <img src="{{ asset('uploads/productos/' . $registro->imagen) }}"
                                                alt="Imagen actual" style="max-width: 150px; height: auto; border-radius: 8px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary me-md-2"
                                    onclick="window.location.href='{{ route('productos.index') }}'">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('mnuAlmacen').classList.add('menu-open');
    document.getElementById('itemProducto').classList.add('active');
</script>
@endpush
