@extends('autenticacion.app')
@section('titulo', 'DiscZone - Restablecer Contraseña')
@section('contenido')
<div class="login-container">
  <div class="login-card">
    <!-- Header con logo -->
    <div class="login-header">
      <a href="/" class="login-brand">
        <img src="{{ asset('assets/img/nav-logo-img.png') }}" alt="DiscZone" class="login-logo-main" />
      </a>
      <h2 class="mt-3 mb-2 auth-heading">Restablecer Contraseña</h2>
      <p class="auth-sub">Crea una nueva contraseña segura para tu cuenta</p>
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

    <!-- Formulario de restablecimiento -->
    <form action="{{ route('password.update') }}" method="post" id="resetForm" novalidate>
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <!-- Email input -->
      <div class="form-group mb-3">
        <label for="resetEmail" class="form-label">
          <i class="bi bi-envelope me-2"></i>Correo electrónico
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input 
            type="email" 
            id="resetEmail" 
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

      <!-- New Password input -->
      <div class="form-group mb-3">
        <label for="resetPassword" class="form-label">
          <i class="bi bi-lock-fill me-2"></i>Nueva Contraseña
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input 
            type="password" 
            id="resetPassword" 
            name="password" 
            class="form-control @error('password') is-invalid @enderror" 
            placeholder="Mínimo 8 caracteres"
            required
            autocomplete="new-password"
          />
          <button 
            type="button" 
            class="btn btn-outline-secondary toggle-password" 
            data-target="resetPassword"
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

      <!-- Confirm Password input -->
      <div class="form-group mb-3">
        <label for="resetPasswordConfirmation" class="form-label">
          <i class="bi bi-lock-fill me-2"></i>Confirmar Contraseña
        </label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input 
            type="password" 
            id="resetPasswordConfirmation" 
            name="password_confirmation" 
            class="form-control @error('password_confirmation') is-invalid @enderror" 
            placeholder="Repite tu nueva contraseña"
            required
            autocomplete="new-password"
          />
          <button 
            type="button" 
            class="btn btn-outline-secondary toggle-password" 
            data-target="resetPasswordConfirmation"
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

      <!-- Password Strength Indicator (opcional) -->
      <div class="password-strength-indicator mb-3 d-none" id="passwordStrengthIndicator">
        <div class="progress progress-sm">
          <div id="passwordStrengthBar" class="progress-bar" style="width: 0%;"></div>
        </div>
        <small id="passwordStrengthText" class="text-muted mt-1 d-block"></small>
      </div>

      <!-- Botón submit -->
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-login btn-lg">
          <i class="bi bi-check-circle me-2"></i>Restablecer Contraseña
        </button>
      </div>

      <!-- Link a login -->
      <div class="text-center mt-3">
        <p class="text-muted mb-0 small">
          ¿Cambias de opinión? 
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
<script>
  // Password Strength Indicator
  const passwordInput = document.getElementById('resetPassword');
  const passwordStrengthBar = document.getElementById('passwordStrengthBar');
  const passwordStrengthText = document.getElementById('passwordStrengthText');
  const passwordStrengthIndicator = document.getElementById('passwordStrengthIndicator');

  if (passwordInput) {
    passwordInput.addEventListener('input', function() {
      const password = this.value;
      let strength = 0;
      let text = '';
      let color = '';

      if (password.length === 0) {
        passwordStrengthIndicator.style.display = 'none';
        return;
      }

      passwordStrengthIndicator.style.display = 'block';

      if (password.length >= 8) strength += 25;
      if (password.length >= 12) strength += 25;
      if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
      if (/[0-9]/.test(password)) strength += 12.5;
      if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) strength += 12.5;

      if (strength < 25) {
        text = 'Muy débil';
        color = '#ef4444';
      } else if (strength < 50) {
        text = 'Débil';
        color = '#f59e0b';
      } else if (strength < 75) {
        text = 'Media';
        color = '#f59e0b';
      } else {
        text = 'Fuerte';
        color = '#22c55e';
      }

      passwordStrengthBar.style.width = strength + '%';
      passwordStrengthBar.style.backgroundColor = color;
      passwordStrengthText.textContent = text;
      passwordStrengthText.style.color = color;
    });
  }
</script>
@endpush
@endsection