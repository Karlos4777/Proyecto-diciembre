@extends('autenticacion.app')
@section('titulo', 'DiscZone - Iniciar Sesión')
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

    <!-- Formulario de login -->
    <form action="{{ route('login.post') }}" method="post" id="loginForm" novalidate>
      @csrf

      <!-- Email input -->
      <div class="form-group mb-3">
        <label for="loginEmail" class="form-label">
          <i class="bi bi-envelope me-2"></i>Correo electrónico
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input 
            type="email" 
            id="loginEmail" 
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
      <div class="form-group mb-2">
        <label for="loginPassword" class="form-label">
          <i class="bi bi-lock-fill me-2"></i>Contraseña
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input 
            type="password" 
            id="loginPassword" 
            name="password" 
            class="form-control @error('password') is-invalid @enderror" 
            placeholder="Ingrese su contraseña"
            required
            autocomplete="current-password"
          />
          <button 
            type="button" 
            class="btn btn-outline-secondary toggle-password" 
            data-target="loginPassword"
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

      <!-- Recordar y recuperar contraseña -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input 
            type="checkbox" 
            id="rememberMe" 
            name="remember" 
            class="form-check-input"
            {{ old('remember') ? 'checked' : '' }}
          />
          <label class="form-check-label" for="rememberMe">
            Recuérdame
          </label>
        </div>
        <a href="{{ route('password.request') }}" class="text-decoration-none forgot-password">
          ¿Olvidaste tu contraseña?
        </a>
      </div>

      <!-- Botón submit -->
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-login btn-lg">
          <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
        </button>
      </div>
    </form>

    <!-- Divider -->
    <div class="login-divider">
      <span>¿No tienes cuenta?</span>
    </div>

    <!-- Link a registro -->
    <div class="text-center">
      <p class="text-muted mb-0">
        Contacta al administrador para crear una cuenta
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