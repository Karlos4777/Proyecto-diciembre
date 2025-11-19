@extends('autenticacion.app')
@section('titulo', 'DiscZone - Recuperar Contraseña')
@section('contenido')
<div class="login-container">
  <div class="login-card">
    <!-- Header con logo -->
    <div class="login-header">
      <a href="/" class="login-brand">
        <img src="{{ asset('assets/img/nav-logo-img.png') }}" alt="DiscZone" class="login-logo-main" />
      </a>
      <h2 class="mt-3 mb-2" style="color: var(--color-dark-brown); font-weight: 700;">Recuperar Contraseña</h2>
      <p style="color: #666; font-size: 0.9rem;">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña</p>
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

    @if(session('mensaje'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('mensaje') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- Formulario de recuperación -->
    <form action="{{ route('password.send-link') }}" method="post" id="recoveryForm" novalidate>
      @csrf

      <!-- Email input -->
      <div class="form-group mb-3">
        <label for="recoveryEmail" class="form-label">
          <i class="bi bi-envelope me-2"></i>Correo electrónico
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input 
            type="email" 
            id="recoveryEmail" 
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

      <!-- Botón submit -->
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-login btn-lg">
          <i class="bi bi-send me-2"></i>Enviar enlace de recuperación
        </button>
      </div>

      <!-- Link a login -->
      <div class="text-center mt-3">
        <p class="text-muted mb-0 small">
          ¿Recuerdas tu contraseña? 
          <a href="{{ route('login') }}" class="text-decoration-none forgot-password">
            Vuelve al login
          </a>
        </p>
      </div>
    </form>
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