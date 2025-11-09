@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Catálogo</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ isset($registro) ? route('catalogo.update', $registro->id) : route('catalogo.store') }}" 
                              method="POST" id="formRegistroCatalogo">

                            @csrf
                            @if(isset($registro))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre', $registro->nombre ?? '') }}" 
                                           required>
                                    @error('nombre')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea name="descripcion" 
                                              class="form-control @error('descripcion') is-invalid @enderror" 
                                              id="descripcion" 
                                              rows="4">{{ old('descripcion', $registro->descripcion ?? '') }}</textarea>
                                    @error('descripcion')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary me-md-2"
                                        onclick="window.location.href='{{ route('catalogo.index') }}';">Cancelar</button>
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
    document.getElementById('mnuSeguridad')?.classList.add('menu-open');
    document.getElementById('itemCatalogo')?.classList.add('active');
</script>
@endpush
