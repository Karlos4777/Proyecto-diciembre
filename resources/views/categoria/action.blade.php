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
                        <h3 class="card-title">Categoria</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <form action="{{ isset($registro) ? route('categoria.update', $registro->id) : route('categoria.store') }}" 
                         method="POST" id="formRegistroCategoria">

                            @csrf
                            @if(isset($registro))
                                @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">Nombre</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                     id="name" name="name" value="{{old('name', $registro->name ??'')}}" required>
                                     @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                     @enderror
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea name="description" class="form-control" id="description" 
                                    rows="4">{{ old('description', $registro->descripcion ?? '') }}</textarea>
                                     @error('description')
                                        <small class="text-danger">{{$message}}</small>
                                     @enderror
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary me-md-2"
                                    onclick="window.location.href='{{route('categoria.index')}}';">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
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
<script>
    document.getElementById('mnuSeguridad').classList.add('menu-open');
    document.getElementById('itemCategoria').classList.add('active');
</script>
@endpush