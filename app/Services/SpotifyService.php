<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SpotifyService
{
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;

    public function __construct()
    {
        $this->clientId = env('SPOTIFY_CLIENT_ID');
        $this->clientSecret = env('SPOTIFY_CLIENT_SECRET');
    }

    /**
     * Obtiene el token de acceso de Spotify usando Client Credentials Flow
     */
    protected function getAccessToken()
    {
        // Cache el token por 50 minutos (expira en 1 hora)
        return Cache::remember('spotify_access_token', 3000, function () {
            $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            return null;
        });
    }

    /**
     * Busca una canción en Spotify por nombre de artista y canción
     * @param string $query Búsqueda (ej: "artista canción")
     * @return array|null Datos del track (nombre, artista, album, preview_url)
     */
    public function searchTrack(string $query)
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return null;
        }

        $response = Http::withToken($token)->get('https://api.spotify.com/v1/search', [
            'q' => $query,
            'type' => 'track',
            'limit' => 1,
        ]);

        if ($response->successful() && isset($response->json()['tracks']['items'][0])) {
            $track = $response->json()['tracks']['items'][0];
            
            return [
                'name' => $track['name'],
                'artist' => $track['artists'][0]['name'] ?? '',
                'album' => $track['album']['name'] ?? '',
                'preview_url' => $track['preview_url'] ?? null,
                'image' => $track['album']['images'][0]['url'] ?? null,
                'upc' => $track['album']['external_ids']['upc'] ?? null,
                'isrc' => $track['external_ids']['isrc'] ?? null,
            ];
        }

        return null;
    }

    /**
     * Busca por código de barras UPC/EAN
     * @param string $barcode Código UPC/EAN
     * @return array|null Datos del álbum o track
     */
    public function searchByBarcode(string $barcode)
    {
        $token = $this->getAccessToken();
        
        if (!$token) {
            return null;
        }

        // Buscar álbum por UPC
        $response = Http::withToken($token)->get('https://api.spotify.com/v1/search', [
            'q' => 'upc:' . $barcode,
            'type' => 'album',
            'limit' => 1,
        ]);

        if ($response->successful() && isset($response->json()['albums']['items'][0])) {
            $album = $response->json()['albums']['items'][0];
            
            // Obtener el primer track del álbum para el preview
            $tracksResponse = Http::withToken($token)->get($album['href'] . '/tracks', ['limit' => 1]);
            $firstTrack = $tracksResponse->successful() && isset($tracksResponse->json()['items'][0]) 
                ? $tracksResponse->json()['items'][0] 
                : null;
            
            return [
                'name' => $album['name'],
                'artist' => $album['artists'][0]['name'] ?? '',
                'album' => $album['name'],
                'preview_url' => $firstTrack['preview_url'] ?? null,
                'image' => $album['images'][0]['url'] ?? null,
                'upc' => $barcode,
                'release_date' => $album['release_date'] ?? null,
            ];
        }

        return null;
    }
}
