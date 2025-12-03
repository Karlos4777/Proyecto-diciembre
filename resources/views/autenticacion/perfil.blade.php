@extends('web.app')
@section('contenido')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white p-4" style="background: linear-gradient(135deg, var(--color-dark-brown) 0%, var(--color-black) 100%); border-bottom: 4px solid var(--color-accent);">
                    <h3 class="card-title m-0 d-flex align-items-center">
                        <i class="bi bi-person-circle me-3 fs-2 text-accent" style="color: var(--color-accent);"></i>
                        <div>
                            <span class="d-block fw-bold">Mi Perfil</span>
                            <small class="fs-6 fw-normal opacity-75">Administra tu información personal</small>
                        </div>
                    </h3>
                </div>
                
                <div class="card-body p-4 p-md-5 bg-light">
                    @if (session('mensaje'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-start border-success border-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('mensaje') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('perfil.update')}}" method="POST" id="formRegistroUsuario">
                        @csrf
                        @method('PUT')
                        
                        <h5 class="mb-4 pb-2 border-bottom" style="color: var(--color-brown);">
                            <i class="bi bi-card-heading me-2"></i> Datos Personales
                        </h5>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-secondary">Nombre Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                     id="name" name="name" value="{{old('name', $registro->name ??'')}}" required placeholder="Tu nombre">
                                </div>
                                @error('name')
                                    <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold text-secondary">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                     id="email" name="email" value="{{old('email',  $registro->email ??'')}}" required placeholder="nombre@ejemplo.com">
                                </div>
                                @error('email')
                                    <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{$message}}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="telefono" class="form-label fw-semibold text-secondary">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('telefono') is-invalid @enderror"
                                     id="telefono" name="telefono" value="{{ old('telefono', $registro->telefono ?? '') }}" placeholder="+57 300 123 4567">
                                </div>
                                @error('telefono')
                                    <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="direccion" class="form-label fw-semibold text-secondary">Dirección de Envío</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 @error('direccion') is-invalid @enderror"
                                     id="direccion" name="direccion" value="{{ old('direccion', $registro->direccion ?? '') }}" placeholder="Calle 123 # 45-67">
                                </div>
                                @error('direccion')
                                    <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <h5 class="mb-4 pb-2 border-bottom mt-5" style="color: var(--color-brown);">
                            <i class="bi bi-shield-lock me-2"></i> Seguridad
                        </h5>
                        <div class="alert alert-light border mb-4">
                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Deja estos campos vacíos si no deseas cambiar tu contraseña.</small>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold text-secondary">Nueva Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                     id="password" name="password" placeholder="********">
                                </div>
                                @error('password')
                                    <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold text-secondary">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-check2-square"></i></span>
                                    <input type="password" class="form-control border-start-0 ps-0 @error('password_confirmation') is-invalid @enderror"
                                     id="password_confirmation" name="password_confirmation" placeholder="********">
                                </div>
                                @error('password_confirmation')
                                    <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{$message}}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                            <a href="{{ route('web.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-lg me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-outline-brown px-4 shadow-sm">
                                <i class="bi bi-save me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection