<?php

use App\Http\Middleware\EsAdmin;
use App\Livewire\Admin\Categorias;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ProyectoFormulario;
use App\Livewire\Admin\ProyectosIndex;
use App\Livewire\Admin\Subcategorias;
use App\Livewire\Catalogo;
use App\Livewire\Cliente;
use App\Livewire\Inicio;
use App\Livewire\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Web pública
|--------------------------------------------------------------------------
*/
Route::get('/', Inicio::class)->name('inicio');                  // 1. Pantalla de inicio (logo)
Route::get('/cliente', Cliente::class)->name('cliente');        // 2-3. Continuar como cliente → opciones
Route::get('/catalogo', Catalogo::class)->name('catalogo');     // Ver catálogo (fotos que sube el admin)

// Enlaces de la versión anterior
Route::redirect('/proyectos', '/catalogo');
Route::redirect('/proyectos/{slug}', '/catalogo');
Route::redirect('/galeria', '/catalogo');

/*
|--------------------------------------------------------------------------
| 2. Cotización: el cliente toca una foto y se le abre WhatsApp (ver Support/Whatsapp.php).
|    Se deja esta redirección por si alguien tiene guardado el enlace anterior.
|--------------------------------------------------------------------------
*/
Route::redirect('/cotizar', '/catalogo');

/*
|--------------------------------------------------------------------------
| 3. Acceso y panel de administración privado
|--------------------------------------------------------------------------
*/
Route::get('/login', Login::class)->name('login');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('inicio');
})->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', EsAdmin::class])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/proyectos', ProyectosIndex::class)->name('proyectos');
    Route::get('/proyectos/nuevo', ProyectoFormulario::class)->name('proyectos.crear');
    Route::get('/proyectos/{proyecto}/editar', ProyectoFormulario::class)->name('proyectos.editar');
    Route::get('/categorias', Categorias::class)->name('categorias');
    Route::get('/subcategorias', Subcategorias::class)->name('subcategorias');
});
