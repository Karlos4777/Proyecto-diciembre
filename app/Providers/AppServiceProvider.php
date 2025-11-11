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
public function boot(): void
{
    Paginator::useBootstrap();

    View::composer('*', function ($view) {
        $view->with('categorias', \App\Models\Categoria::all());
        $view->with('catalogos', \App\Models\Catalogo::all());
    });
        
View::composer('*', function ($view) {
    $count = 0;

    if (Auth::check()) {
        $carrito = \App\Models\Carrito::where('user_id', Auth::id())->first();
        $contenido = $carrito ? $carrito->contenido : [];
        $count = is_array($contenido) ? array_sum(array_column($contenido, 'cantidad')) : 0;
    } else {
        $carrito = session('carrito', []);
        $count = is_array($carrito) ? array_sum(array_column($carrito, 'cantidad')) : 0;
    }

    $view->with('cartCount', $count);
});
}
}