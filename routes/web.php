<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PerfilController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PromotionController;

//  Rutas públicas del sitio web
Route::get('/', [WebController::class, 'index'])->name('web.index');
Route::get('/producto/{id}', [WebController::class, 'show'])->name('web.show');

// Ruta para búsqueda AJAX de productos
Route::get('/buscar-productos', [WebController::class, 'buscarProductosAjax'])->name('buscar.ajax');

// Nueva ruta para mostrar productos por categoría
Route::get('/categoria-web/{id}', [CategoriaController::class, 'show'])->name('web.categoria.show');

//  Rutas protegidas (solo usuarios autenticados)
Route::middleware(['auth'])->group(function(){
    Route::resource('usuarios', UserController::class);
    Route::patch('usuarios/{usuario}/toggle', [UserController::class, 'toggleStatus'])->name('usuarios.toggle');
    Route::resource('roles', RoleController::class);
    Route::resource('productos', ProductoController::class);
    // Ruta para asignar promociones (form admin simple)
    Route::post('/productos/promocion/assign', [PromotionController::class, 'assign'])->name('productos.promocion.assign');
    // Ruta para buscar en Spotify
    Route::post('/productos/spotify/search', [ProductoController::class, 'buscarSpotify'])->name('productos.spotify.search');
    Route::resource('categoria', CategoriaController::class);
    Route::resource('catalogo', CatalogoController::class);
    
    Route::get('/carrito', [CarritoController::class, 'mostrar'])->name('carrito.mostrar');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::get('/carrito/sumar/{producto_id}', [CarritoController::class, 'sumar'])->name('carrito.sumar');
Route::get('/carrito/restar/{producto_id}', [CarritoController::class, 'restar'])->name('carrito.restar');
Route::get('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::get('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
Route::post('/carrito/canjear-puntos', [CarritoController::class, 'canjearPuntos'])->name('carrito.canjear.puntos');
Route::get('/carrito/quitar-puntos', [CarritoController::class, 'quitarPuntos'])->name('carrito.quitar.puntos');

    Route::get('/pedido/formulario', [PedidoController::class, 'formulario'])->name('pedido.formulario');
    Route::post('/pedido/realizar', [PedidoController::class, 'realizar'])->name('pedido.realizar');
    Route::get('/perfil/pedidos', [PedidoController::class, 'index'])->name('perfil.pedidos');
    Route::post('/pedidos/{id}/referencias', [PedidoController::class, 'uploadReferencia'])->name('pedidos.referencia.upload');
    Route::patch('/pedidos/{id}/estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiar.estado');    

    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Favoritos (Wishlist)
    Route::get('/favoritos', [\App\Http\Controllers\WishlistController::class, 'index'])->name('favoritos.index');
    Route::post('/favoritos/{producto}/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('favoritos.toggle');

    // Reseñas
    Route::post('/reviews/{producto}', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Reportes CSV
    Route::get('/reportes/productos.csv', [\App\Http\Controllers\ReportController::class, 'productosCsv'])->name('reportes.productos.csv');
    Route::get('/reportes/pedidos.csv', [\App\Http\Controllers\ReportController::class, 'pedidosCsv'])->name('reportes.pedidos.csv');
    // Reportes PDF
    Route::get('/reportes/productos.pdf', [\App\Http\Controllers\ReportController::class, 'productosPdf'])->name('reportes.productos.pdf');
    Route::get('/reportes/pedidos.pdf', [\App\Http\Controllers\ReportController::class, 'pedidosPdf'])->name('reportes.pedidos.pdf');
    // Reportes Excel
    Route::get('/reportes/productos.xlsx', [\App\Http\Controllers\ReportController::class, 'productosExcel'])->name('reportes.productos.excel');
    Route::get('/reportes/pedidos.xlsx', [\App\Http\Controllers\ReportController::class, 'pedidosExcel'])->name('reportes.pedidos.excel');

    Route::post('logout', function(){
        Auth::logout();
        return redirect('/login');
    })->name('logout');

    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

//  Rutas de autenticación (solo para invitados)
Route::middleware('guest')->group(function(){
    Route::get('login', function(){
        return view('autenticacion.login');
    })->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/registro', [RegisterController::class, 'showRegistroForm'])->name('registro');
    Route::post('/registro', [RegisterController::class, 'registrar'])->name('registro.store');

    Route::get('password/reset', [ResetPasswordController::class, 'showRequestForm'])->name('password.request');
    Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.send-link');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
    
});
