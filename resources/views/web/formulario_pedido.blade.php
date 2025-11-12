@extends('web.app')

@section('contenido')

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-credit-card me-2"></i> Completar Compra
                    </h4>
                </div>

                <div class="card-body p-4">
                    <!-- Mostrar errores de validación -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading">
                                <i class="bi bi-exclamation-circle me-2"></i> Por favor revisa los errores:
                            </h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Resumen del carrito -->
                    <div class="card mb-4 bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Resumen de tu compra</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        @php $total = 0 @endphp
                                        @foreach($carrito as $item)
                                            @php $subtotal = $item['precio'] * $item['cantidad']; $total += $subtotal; @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{ $item['nombre'] }}</strong><br>
                                                    <small class="text-muted">{{ $item['cantidad'] }} x ${{ number_format($item['precio'], 2) }}</small>
                                                </td>
                                                <td class="text-end">
                                                    <strong>${{ number_format($subtotal, 2) }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Total a pagar:</h6>
                                <h5 class="mb-0 text-success"><strong>${{ number_format($total, 2) }}</strong></h5>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form action="{{ route('pedido.realizar') }}" method="POST" id="formPedido">
                        @csrf

                        <h5 class="mb-3 mt-4">
                            <i class="bi bi-person-lines-fill me-2"></i> Datos de entrega
                        </h5>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo *</label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', auth()->user()->name) }}"
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico *</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', auth()->user()->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono *</label>
                            <input type="text" 
                                   name="telefono" 
                                   id="telefono" 
                                   class="form-control @error('telefono') is-invalid @enderror" 
                                   value="{{ old('telefono', auth()->user()->telefono ?? '') }}"
                                   required>
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección *</label>
                            <input type="text" 
                                   name="direccion" 
                                   id="direccion" 
                                   class="form-control @error('direccion') is-invalid @enderror" 
                                   placeholder="Calle, número, apartamento, ciudad..."
                                   value="{{ old('direccion', auth()->user()->direccion ?? '') }}"
                                   required>
                            @error('direccion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="metodo_pago" class="form-label">Método de pago *</label>
                            <select name="metodo_pago" 
                                    id="metodo_pago" 
                                    class="form-select @error('metodo_pago') is-invalid @enderror" 
                                    required>
                                <option value="">-- Seleccione un método --</option>
                                <option value="tarjeta" @selected(old('metodo_pago') === 'tarjeta')>
                                    <i class="bi bi-credit-card me-1"></i> Tarjeta de crédito/débito
                                </option>
                                <option value="nequi" @selected(old('metodo_pago') === 'nequi')>
                                    <i class="bi bi-phone me-1"></i> Nequi
                                </option>
                                <option value="efectivo" @selected(old('metodo_pago') === 'efectivo')>
                                    <i class="bi bi-cash-coin me-1"></i> Efectivo contra entrega
                                </option>
                            </select>
                            @error('metodo_pago')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i> Confirmar compra
                            </button>
                            <a href="{{ route('carrito.mostrar') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Volver al carrito
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light text-muted text-center py-3">
                    <small>
                        <i class="bi bi-lock-fill me-1"></i> Tu información está protegida y encriptada
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endpush