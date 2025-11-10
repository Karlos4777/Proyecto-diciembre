<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Catalogo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🔹 Usa Bootstrap para la paginación
        Paginator::useBootstrap();

        // 🔹 Hace disponibles las categorías y catálogos en todas las vistas
        View::composer('*', function ($view) {
            $view->with('categorias', Categoria::all());
            $view->with('catalogos', Catalogo::all());
            
        View::composer('*', function ($view) {
        $count = 0;

        if (Auth::check()) {
            $car = Carrito::where('user_id', Auth::id())->first();
            $contenido = $car ? $car->contenido : [];
            $count = is_array($contenido) ? array_sum(array_column($contenido, 'cantidad')) : 0;
        } else {
            $sessionCart = session('carrito', []);
            $sessionCart = is_array($sessionCart) ? $sessionCart : (is_object($sessionCart) ? (array) $sessionCart : []);
            $count = $sessionCart ? array_sum(array_column($sessionCart, 'cantidad')) : 0;
        }

        $view->with('cartCount', $count);
    });
        });
    }
}
