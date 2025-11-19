@extends('autenticacion.app')
@section('titulo', 'DiscZone - Registro')
@section('contenido')
<div class="login-container">
  <div class="login-card">
    <!-- Header con logo -->
    <div class="login-header">
      <a href="/" class="login-brand">
        <img src="{{ asset('assets/img/nav-logo-img.png') }}" alt="DiscZone" class="login-logo-main" />
      </a>
    </div>

    <!-- Mensajes de error/éxito -->
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <strong>Error de validación:</strong>
        <ul class="mb-0 mt-2">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('message') || session('mensaje'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('message') ?? session('mensaje') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- Título de registro -->
    <h4 class="text-center mb-4">
      <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta
    </h4>

    <!-- Formulario de registro -->
    <form action="{{ route('registro.store') }}" method="POST" id="registroForm" novalidate>
      @csrf

      <!-- Nombre input -->
      <div class="form-group mb-3">
        <label for="registroNombre" class="form-label">
          <i class="bi bi-person me-2"></i>Nombre completo
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input 
            type="text" 
            id="registroNombre" 
            name="name" 
            class="form-control @error('name') is-invalid @enderror" 
            value="{{ old('name') }}"
            placeholder="Tu nombre completo"
            required
          />
          @error('name')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
      </div>

      <!-- Email input -->
      <div class="form-group mb-3">
        <label for="registroEmail" class="form-label">
          <i class="bi bi-envelope me-2"></i>Correo electrónico
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input 
            type="email" 
            id="registroEmail" 
            name="email" 
            class="form-control @error('email') is-invalid @enderror" 
            value="{{ old('email') }}"
            placeholder="usuario@ejemplo.com"
            required
            autocomplete="email"
          />
          @error('email')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
      </div>

      <!-- Password input -->
      <div class="form-group mb-3">
        <label for="registroPassword" class="form-label">
          <i class="bi bi-lock-fill me-2"></i>Contraseña
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input 
            type="password" 
            id="registroPassword" 
            name="password" 
            class="form-control @error('password') is-invalid @enderror" 
            placeholder="Mínimo 8 caracteres"
            required
            autocomplete="new-password"
          />
          <button 
            type="button" 
            class="btn btn-outline-secondary toggle-password" 
            data-target="registroPassword"
            title="Mostrar/ocultar contraseña"
          >
            <i class="bi bi-eye"></i>
          </button>
          @error('password')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
      </div>

      <!-- Password confirmation input -->
      <div class="form-group mb-3">
        <label for="registroPasswordConfirm" class="form-label">
          <i class="bi bi-lock-fill me-2"></i>Confirmar contraseña
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input 
            type="password" 
            id="registroPasswordConfirm" 
            name="password_confirmation" 
            class="form-control @error('password_confirmation') is-invalid @enderror" 
            placeholder="Confirme su contraseña"
            required
            autocomplete="new-password"
          />
          <button 
            type="button" 
            class="btn btn-outline-secondary toggle-password" 
            data-target="registroPasswordConfirm"
            title="Mostrar/ocultar contraseña"
          >
            <i class="bi bi-eye"></i>
          </button>
          @error('password_confirmation')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
      </div>

      <!-- Botón submit -->
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-login btn-lg">
          <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta
        </button>
      </div>
    </form>

    <!-- Divider -->
    <div class="login-divider">
      <span>¿Ya tienes cuenta?</span>
    </div>

    <!-- Link a login -->
    <div class="text-center">
      <p class="text-muted mb-0">
        <a href="{{ route('login') }}" class="text-decoration-none link-primary">
          <i class="bi bi-box-arrow-in-right me-1"></i>Inicia sesión aquí
        </a>
      </p>
    </div>
  </div>

  <!-- Footer con información -->
  <div class="login-footer">
    <p class="text-center text-muted mb-0">
      <small>© 2025 DiscZone Music Store. Todos los derechos reservados.</small>
    </p>
  </div>
</div>

@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush
@endsection