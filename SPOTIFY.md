# Sistema de Preview Musical (Spotify)

## Descripción
Sistema integrado que permite añadir previews de música de Spotify a los productos, ideal para tiendas de discos, música y artículos relacionados.

## Componentes

### 1. Base de Datos
- **Migración**: `2025_11_26_221325_add_spotify_fields_to_productos_table.php`
- **Campos añadidos**:
  - `artista` (string, nullable): Nombre del artista
  - `album` (string, nullable): Nombre del álbum
  - `preview_url` (string, nullable): URL del preview de 30 segundos de Spotify

### 2. Modelo
- **Archivo**: `app/Models/Producto.php`
- Los campos de Spotify están en el `$fillable` para permitir asignación masiva.

### 3. Servicio de Spotify
- **Archivo**: `app/Services/SpotifyService.php`
- **Método principal**: `searchTrack(string $query)`
- **Funcionalidad**:
  - Obtiene token de acceso usando Client Credentials Flow
  - Cachea el token por 50 minutos
  - Busca tracks en la API de Spotify
  - Retorna: nombre, artista, álbum, preview_url e imagen

### 4. Configuración
Añade a tu archivo `.env`:
```env
SPOTIFY_CLIENT_ID=tu_client_id
SPOTIFY_CLIENT_SECRET=tu_client_secret
```

**Para obtener las credenciales**:
1. Ve a [Spotify for Developers](https://developer.spotify.com/dashboard)
2. Crea una nueva app
3. Copia el Client ID y Client Secret

### 5. Vistas

#### Vista de Administración (`producto/action.blade.php`)
- Campos añadidos para editar:
  - Artista
  - Álbum
  - Preview URL

#### Vista Pública (`web/item.blade.php`)
- Muestra un reproductor de audio HTML5
- Incluye información del artista y álbum
- Solo se muestra si existe `preview_url`

## Uso

### Opción 1: Manual (Admin)
1. Edita un producto en el panel de administración
2. Busca la canción en Spotify y copia el preview URL
3. Completa los campos: Artista, Álbum y Preview URL
4. Guarda el producto

### Opción 2: Búsqueda Automática (Opcional)
Para implementar búsqueda automática desde el admin:

```php
// En el controlador de productos
use App\Services\SpotifyService;

public function buscarSpotify(Request $request, SpotifyService $spotify)
{
    $query = $request->input('query'); // ej: "Queen Bohemian Rhapsody"
    $resultado = $spotify->searchTrack($query);
    
    return response()->json($resultado);
}
```

Añadir ruta:
```php
Route::post('/productos/spotify/search', [ProductoController::class, 'buscarSpotify'])
    ->name('productos.spotify.search');
```

### Opción 3: Obtener Preview URL de Spotify
1. Busca la canción en Spotify Web
2. Click derecho en la canción > Compartir > Copiar enlace de canción
3. El formato será: `https://open.spotify.com/track/ID`
4. Usa la API o busca manualmente el preview en:
   ```
   https://api.spotify.com/v1/tracks/ID
   ```

## Ejemplo de Preview URL
```
https://p.scdn.co/mp3-preview/abc123...
```

## Notas Técnicas
- Los previews de Spotify tienen una duración de ~30 segundos
- El token de acceso se cachea automáticamente
- No requiere autenticación de usuario (usa Client Credentials)
- Compatible con todos los navegadores modernos

## Estilos Personalizados (Opcional)
Puedes estilizar el reproductor de audio añadiendo CSS personalizado:

```css
audio::-webkit-media-controls-panel {
    background-color: #f8f9fa;
}
```

## Seguridad
- Las credenciales de Spotify nunca se exponen al cliente
- El token se almacena en caché del servidor
- La URL del preview es pública y temporal (proporcionada por Spotify)
