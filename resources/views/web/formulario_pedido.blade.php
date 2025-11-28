@extends('web.app')

@section('contenido')

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white" style="background-color: #6F4E37;">
                    <h4 class="mb-0 product-title text-white">
                        <i class="bi bi-credit-card me-2"></i> Completar Compra
                    </h4>
                </div>

                <div class="card-body p-4" style="background-color: #ffffff;">
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
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body" style="background-color: #fbfaf9; border-left: 4px solid #6F4E37;">
                            <h5 class="card-title mb-3" style="color: #6F4E37;">Resumen de tu compra</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead style="background-color: #4A2F1E; color: white;">
                                        <tr>
                                            <th class="py-2" style="width: 60px;">Imagen</th>
                                            <th class="py-2">Producto</th>
                                            <th class="py-2 text-center" style="width: 80px;">Código</th>
                                            <th class="py-2 text-center" style="width: 100px;">Categoría</th>
                                            <th class="py-2 text-center" style="width: 60px;">Cant.</th>
                                            <th class="py-2 text-end" style="width: 90px;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0 @endphp
                                        @foreach($carrito as $item)
                                            @php $subtotal = $item['precio'] * $item['cantidad']; $total += $subtotal; @endphp
                                            <tr>
                                                <td>
                                                    @if(isset($item['imagen']))
                                                         <img src="{{ asset('uploads/productos/' . $item['imagen']) }}" 
                                                             alt="{{ $item['nombre'] }}"
                                                             class="rounded shadow-sm"
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong class="d-block" style="color: #4A2F1E;">{{ $item['nombre'] }}</strong>
                                                    <small class="text-muted">${{ number_format($item['precio'], 2) }} c/u</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary small">{{ $item['codigo'] ?? '-' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge small" style="background-color: #efe3d8; color: #6F4E37;">
                                                        {{ $item['categoria'] ?? 'Sin categoría' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ $item['cantidad'] }}</strong>
                                                </td>
                                                <td class="text-end">
                                                    <strong style="color: #4A2F1E;">${{ number_format($subtotal, 2) }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <hr style="border-color: #6F4E37; opacity: 0.2;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0" style="color: #6F4E37;">Total a pagar:</h6>
                                <h5 class="mb-0 product-price"><strong>${{ number_format($total, 2) }}</strong></h5>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form action="{{ route('pedido.realizar') }}" method="POST" id="formPedido" enctype="multipart/form-data">
                        @csrf

                        <h5 class="mb-3 mt-4" style="color: #6F4E37;">
                            <i class="bi bi-person-lines-fill me-2"></i> Datos de entrega
                        </h5>

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold" style="color: #4A2F1E;">Nombre completo *</label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', auth()->user()->name) }}"
                                   required
                                   style="border-color: #e0e0e0;">
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold" style="color: #4A2F1E;">Correo electrónico *</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', auth()->user()->email) }}"
                                   required
                                   style="border-color: #e0e0e0;">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label fw-semibold" style="color: #4A2F1E;">Teléfono *</label>
                            <input type="text" 
                                   name="telefono" 
                                   id="telefono" 
                                   class="form-control @error('telefono') is-invalid @enderror" 
                                   value="{{ old('telefono', auth()->user()->telefono ?? '') }}"
                                   required
                                   style="border-color: #e0e0e0;">
                            @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label fw-semibold" style="color: #4A2F1E;">Dirección *</label>
                            <input type="text" 
                                   name="direccion" 
                                   id="direccion" 
                                   class="form-control @error('direccion') is-invalid @enderror" 
                                   placeholder="Calle, número, apartamento, ciudad..."
                                   value="{{ old('direccion', auth()->user()->direccion ?? '') }}"
                                   required
                                   style="border-color: #e0e0e0;">
                            @error('direccion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="metodo_pago" class="form-label fw-semibold" style="color: #4A2F1E;">Método de pago *</label>
                            <select name="metodo_pago" 
                                    id="metodo_pago" 
                                    class="form-select @error('metodo_pago') is-invalid @enderror" 
                                    required
                                    style="border-color: #e0e0e0;">
                                <option value="">-- Seleccione un método --</option>
                                <option value="tarjeta" @selected(old('metodo_pago') === 'tarjeta')>
                                    Tarjeta de crédito/débito
                                </option>
                                <option value="nequi" @selected(old('metodo_pago') === 'nequi')>
                                    Nequi
                                </option>
                                <option value="efectivo" @selected(old('metodo_pago') === 'efectivo')>
                                    Efectivo contra entrega
                                </option>
                            </select>
                            @error('metodo_pago')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Detalles de pago dinámicos -->
                        <div id="info-tarjeta" class="payment-info mb-4 d-none p-3 rounded" style="background-color: #fbfaf9; border: 1px solid #e0e0e0;">
                            <h6 class="mb-3" style="color: #6F4E37;"><i class="bi bi-credit-card-2-front me-2"></i>Datos de la Tarjeta</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small text-muted">Número de Tarjeta</label>
                                    <input type="text" class="form-control" placeholder="0000 0000 0000 0000" style="border-color: #e0e0e0;">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Fecha Expiración</label>
                                    <input type="text" class="form-control" placeholder="MM/YY" style="border-color: #e0e0e0;">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">CVV</label>
                                    <input type="text" class="form-control" placeholder="123" style="border-color: #e0e0e0;">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small text-muted">Nombre en la tarjeta</label>
                                    <input type="text" class="form-control" placeholder="Como aparece en la tarjeta" style="border-color: #e0e0e0;">
                                </div>
                            </div>
                        </div>

                        <div id="info-nequi" class="payment-info mb-4 d-none p-3 rounded text-center" style="background-color: #fbfaf9; border: 1px solid #e0e0e0;">
                            <h6 class="mb-2" style="color: #6F4E37;"><i class="bi bi-phone me-2"></i>Transferencia Nequi</h6>
                            <p class="mb-1 text-muted">Realiza tu transferencia al siguiente número:</p>
                            <h4 class="my-3" style="color: #4A2F1E; letter-spacing: 1px;">300 123 4567</h4>
                            <small class="text-muted d-block">Empresa: <strong>DisZone</strong></small>
                            <div class="mt-3 alert alert-warning py-2 small">
                                <i class="bi bi-info-circle me-1"></i> Envía el comprobante al finalizar
                            </div>
                        </div>

                        <div id="info-efectivo" class="payment-info mb-4 d-none p-3 rounded" style="background-color: #fbfaf9; border: 1px solid #e0e0e0;">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-cash-coin fs-4 me-3" style="color: #6F4E37;"></i>
                                <div>
                                    <h6 class="mb-1" style="color: #6F4E37;">Pago Contra Entrega</h6>
                                    <p class="mb-0 small text-muted">
                                        Pagarás en efectivo al momento de recibir tu pedido. Por favor ten el dinero exacto si es posible.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-brown btn-lg">
                                <i class="bi bi-check-circle me-2"></i> Confirmar compra
                            </button>
                            <a href="{{ route('carrito.mostrar') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Volver al carrito
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-muted text-center py-3" style="background-color: #fbfaf9; border-top: 1px solid #eee;">
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
        border-color: #6F4E37 !important;
        box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25) !important;
    }
    .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectMetodo = document.getElementById('metodo_pago');
        const infoTarjeta = document.getElementById('info-tarjeta');
        const infoNequi = document.getElementById('info-nequi');
        const infoEfectivo = document.getElementById('info-efectivo');

        function togglePaymentInfo() {
            // Ocultar todos
            if(infoTarjeta) infoTarjeta.classList.add('d-none');
            if(infoNequi) infoNequi.classList.add('d-none');
            if(infoEfectivo) infoEfectivo.classList.add('d-none');

            // Mostrar el seleccionado
            const valor = selectMetodo.value;
            if (valor === 'tarjeta' && infoTarjeta) {
                infoTarjeta.classList.remove('d-none');
            } else if (valor === 'nequi' && infoNequi) {
                infoNequi.classList.remove('d-none');
            } else if (valor === 'efectivo' && infoEfectivo) {
                infoEfectivo.classList.remove('d-none');
            }
        }

        if(selectMetodo) {
            selectMetodo.addEventListener('change', togglePaymentInfo);
            // Ejecutar al cargar por si hay un valor old() seleccionado
            togglePaymentInfo();
        }
    });
</script>
@endpush