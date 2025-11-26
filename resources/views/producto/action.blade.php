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

                                <!-- Código de Barras (UPC/EAN) -->
                                <div class="col-md-3 mb-3">
                                    <label for="barcode" class="form-label">Código de Barras (UPC/EAN) <i class="bi bi-upc-scan text-muted"></i></label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                                        id="barcode" name="barcode"
                                        value="{{ old('barcode', $registro->barcode ?? '') }}"
                                        placeholder="0123456789012">
                                    @error('barcode')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <div class="mt-2 d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-open-scanner">
                                            <i class="bi bi-camera"></i> Escanear
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-clear-barcode">
                                            Limpiar
                                        </button>
                                        <label class="btn btn-outline-dark btn-sm mb-0" for="barcode_image">
                                            <i class="bi bi-image"></i> Desde imagen
                                        </label>
                                        <input type="file" id="barcode_image" accept="image/*" class="d-none" />
                                    </div>
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
                              <!--cantidad -->
                                <div class="col-md-3 mb-3">
                                    <label for="cantidad" class="form-label">Cantidad</label>
                                    <input type="text" class="form-control @error('cantidad') is-invalid @enderror"
                                        id="cantidad" name="cantidad"
                                        value="{{ old('cantidad', $registro->cantidad ?? '') }}" required>
                                    @error('cantidad')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Categoría -->
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
                                <!-- Catálogo -->
                                <div class="col-md-3 mb-3">
                                    <label for="catalogo_id" class="form-label">Catálogo</label>
                                    <select name="catalogo_id" id="catalogo_id" class="form-control">
                                        <option value="">Seleccione un catálogo</option>
                                        @foreach($catalogos as $catalogo)
                                            <option value="{{ $catalogo->id }}"
                                                {{ isset($registro) && $registro->catalogo_id == $catalogo->id ? 'selected' : '' }}>
                                                {{ $catalogo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('catalogo_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

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
                                                alt="Imagen actual" class="img-max-150" style="border-radius: 8px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <!-- Descuento -->
                                <div class="col-md-3 mb-3">
                                    <label for="descuento" class="form-label">Descuento (%)</label>
                                    <input type="number" class="form-control @error('descuento') is-invalid @enderror"
                                        id="descuento" name="descuento" min="0" max="100" step="1"
                                        value="{{ old('descuento', $registro->descuento ?? 0) }}">
                                    @error('descuento')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Artista -->
                                <div class="col-md-3 mb-3">
                                    <label for="artista" class="form-label">Artista</label>
                                    <input type="text" class="form-control @error('artista') is-invalid @enderror"
                                        id="artista" name="artista"
                                        value="{{ old('artista', $registro->artista ?? '') }}">
                                    @error('artista')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Álbum -->
                                <div class="col-md-3 mb-3">
                                    <label for="album" class="form-label">Álbum</label>
                                    <input type="text" class="form-control @error('album') is-invalid @enderror"
                                        id="album" name="album"
                                        value="{{ old('album', $registro->album ?? '') }}">
                                    @error('album')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Preview URL -->
                                <div class="col-md-3 mb-3">
                                    <label for="preview_url" class="form-label">Preview URL (Spotify)</label>
                                    <input type="url" class="form-control @error('preview_url') is-invalid @enderror"
                                        id="preview_url" name="preview_url"
                                        value="{{ old('preview_url', $registro->preview_url ?? '') }}"
                                        placeholder="https://p.scdn.co/...">
                                    @error('preview_url')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Búsqueda de Spotify -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">
                                                <i class="bi bi-spotify text-success"></i> Búsqueda Automática en Spotify
                                            </h6>
                                            <div class="row g-2">
                                                <div class="col-md-10">
                                                    <div class="input-group mb-2">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="spotify_query" 
                                                            placeholder="Ej: Queen Bohemian Rhapsody">
                                                        <button type="button" class="btn btn-success" id="btn-buscar-spotify">
                                                            <i class="bi bi-music-note-beamed"></i> Buscar por Texto
                                                        </button>
                                                    </div>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-upc-scan"></i>
                                                        </span>
                                                        <input type="text" class="form-control" id="barcode_query" 
                                                            placeholder="0093624946311">
                                                        <button type="button" class="btn btn-info" id="btn-buscar-barcode">
                                                            <i class="bi bi-upc-scan"></i> Buscar por Código Barras
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary" id="btn-auto-fill-barcode" title="Usar código de barras del producto">
                                                            <i class="bi bi-arrow-down-circle"></i>
                                                        </button>
                                                    </div>
                                                    <small class="text-muted">
                                                        Busca por artista y canción, o escanea/pega el código UPC/EAN del CD/vinilo
                                                    </small>
                                                </div>
                                                <div class="col-md-2">
                                                    <div id="spotify-status" class="small"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnBuscar = document.getElementById('btn-buscar-spotify');
    const btnBuscarBarcode = document.getElementById('btn-buscar-barcode');
    const btnAutoFillBarcode = document.getElementById('btn-auto-fill-barcode');
    const queryInput = document.getElementById('spotify_query');
    const barcodeInput = document.getElementById('barcode_query');
    const statusDiv = document.getElementById('spotify-status');

    function buscarSpotify(query, type = 'text') {
        if (!query || query.length < 2) {
            statusDiv.innerHTML = '<span class="text-danger">Por favor ingresa al menos 2 caracteres</span>';
            return;
        }

        if (btnBuscar) btnBuscar.disabled = true;
        if (btnBuscarBarcode) btnBuscarBarcode.disabled = true;
        const activeBtn = type === 'barcode' ? btnBuscarBarcode : btnBuscar;
        const originalHTML = activeBtn ? activeBtn.innerHTML : '';
        if (activeBtn) activeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Buscando...';
        statusDiv.innerHTML = '<span class="text-info">Buscando en Spotify...</span>';

        fetch('{{ route("productos.spotify.search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ query: query, type: type })
        })
        .then(response => { if (!response.ok) throw new Error('No se encontraron resultados'); return response.json(); })
        .then(data => {
            const artistaEl = document.getElementById('artista');
            const albumEl = document.getElementById('album');
            const previewEl = document.getElementById('preview_url');
            const barcodeEl = document.getElementById('barcode');

            if (artistaEl) artistaEl.value = data.artist || '';
            if (albumEl) albumEl.value = data.album || '';
            if (previewEl) previewEl.value = data.preview_url || '';
            if (data.upc && type === 'text' && barcodeEl) barcodeEl.value = data.upc;

            statusDiv.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> ¡Datos cargados!</span>';
            if (!data.preview_url) {
                statusDiv.innerHTML += '<br><span class="text-warning">⚠ Esta canción no tiene preview disponible</span>';
            }
        })
        .catch(error => { statusDiv.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle"></i> ' + error.message + '</span>'; })
        .finally(() => {
            if (btnBuscar) btnBuscar.disabled = false;
            if (btnBuscarBarcode) btnBuscarBarcode.disabled = false;
            if (activeBtn) activeBtn.innerHTML = originalHTML;
        });
    }

    if (btnBuscar) {
        btnBuscar.addEventListener('click', function() { buscarSpotify(queryInput.value.trim(), 'text'); });
        queryInput.addEventListener('keypress', function(e) { if (e.key === 'Enter') { e.preventDefault(); btnBuscar.click(); } });
    }

    if (btnBuscarBarcode) {
        btnBuscarBarcode.addEventListener('click', function() { buscarSpotify(barcodeInput.value.trim(), 'barcode'); });
        barcodeInput.addEventListener('keypress', function(e) { if (e.key === 'Enter') { e.preventDefault(); btnBuscarBarcode.click(); } });
    }

    if (btnAutoFillBarcode) {
        btnAutoFillBarcode.addEventListener('click', function() {
            const productBarcode = document.getElementById('barcode')?.value.trim();
            if (productBarcode) {
                barcodeInput.value = productBarcode;
                statusDiv.innerHTML = '<span class="text-info">Código copiado, ahora haz clic en "Buscar por Código Barras"</span>';
            } else {
                statusDiv.innerHTML = '<span class="text-warning">No hay código de barras en el producto</span>';
            }
        });
    }
    // ----- Escáner de código de barras (QuaggaJS) -----
    const btnOpenScanner = document.getElementById('btn-open-scanner');
    const btnClearBarcode = document.getElementById('btn-clear-barcode');
    const inputBarcodeImage = document.getElementById('barcode_image');
    let quaggaLoaded = false;
    let scannerActive = false;

    function loadQuagga(callback){
        if(quaggaLoaded){ callback(); return; }
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js';
        s.onload = function(){ quaggaLoaded = true; callback(); };
        s.onerror = function(){ alert('No se pudo cargar el escáner.'); };
        document.body.appendChild(s);
    }

    function startScanner(){
        if(!window.Quagga){ return; }
        if(scannerActive){ return; }
        scannerActive = true;
        const overlay = document.createElement('div');
        overlay.id = 'scanner-overlay';
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.background = 'rgba(0,0,0,0.6)';
        overlay.style.zIndex = '1055';
        overlay.innerHTML = `
            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#000;border-radius:8px;overflow:hidden;">
                <div id="scanner" style="width:520px;height:360px;position:relative;"></div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px;background:#111;color:#fff;font-size:12px;">
                    <span>Apunta la cámara al código UPC/EAN</span>
                    <button id="scanner-close" class="btn btn-sm btn-light">Cerrar</button>
                </div>
            </div>`;
        document.body.appendChild(overlay);

        const closeBtn = overlay.querySelector('#scanner-close');
        closeBtn.addEventListener('click', stopScanner);

        Quagga.init({
            inputStream: {
                name: 'Live',
                type: 'LiveStream',
                target: document.querySelector('#scanner'),
                constraints: { facingMode: 'environment' }
            },
            decoder: {
                // Limitar a lectores más comunes para retail
                readers: ['ean_reader','upc_reader']
            },
            locate: true
        }, function(err){
            if(err){ console.error(err); alert('Error inicializando cámara'); stopScanner(); return; }
            Quagga.start();
        });

        Quagga.onDetected(function(result){
            const code = result && result.codeResult && result.codeResult.code;
            if(code){
                document.getElementById('barcode').value = code;
                stopScanner();
            }
        });
    }

    function stopScanner(){
        if(window.Quagga){
            try { Quagga.stop(); Quagga.offDetected(); } catch(e){}
        }
        const overlay = document.getElementById('scanner-overlay');
        if(overlay){ overlay.remove(); }
        scannerActive = false;
    }

    if(btnOpenScanner){
        btnOpenScanner.addEventListener('click', function(){
            loadQuagga(startScanner);
        });
    }
    if(btnClearBarcode){
        btnClearBarcode.addEventListener('click', function(){
            const bc = document.getElementById('barcode');
            if(bc){ bc.value = ''; }
        });
    }

    // Decode desde imagen (fallback sin cámara)
    if(inputBarcodeImage){
        inputBarcodeImage.addEventListener('change', function(){
            const file = this.files && this.files[0];
            if(!file){ return; }
            if(file.size > (3 * 1024 * 1024)){
                statusDiv.innerHTML = '<span class="text-warning">La imagen es muy grande (>3MB). Redúcela o toma otra foto.</span>';
                return;
            }
            loadQuagga(function(){
                const reader = new FileReader();
                reader.onload = function(e){
                    const img = new Image();
                    img.onload = function(){
                        // Redimensionar al ancho máximo 1000px para mejorar detección
                        const maxW = 1000;
                        const scale = Math.min(1, maxW / img.width);
                        const canvas = document.createElement('canvas');
                        canvas.width = Math.floor(img.width * scale);
                        canvas.height = Math.floor(img.height * scale);
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        const dataUrl = canvas.toDataURL('image/jpeg', 0.92);

                        Quagga.decodeSingle({
                            src: dataUrl,
                            numOfWorkers: 0,
                            inputStream: { size: 800 },
                            decoder: { readers: ['ean_reader','upc_reader'] }
                        }, function(result){
                            if(result && result.codeResult){
                                document.getElementById('barcode').value = result.codeResult.code;
                                statusDiv.innerHTML = '<span class="text-success">Código detectado desde imagen</span>';
                            } else {
                                statusDiv.innerHTML = '<span class="text-danger">No se pudo leer el código en la imagen</span>';
                            }
                        });
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        });
    }
});
</script>
@endpush

@endsection

