<?php

use App\Http\Middleware\EsAdmin;
use App\Livewire\Admin\Categorias;
use App\Livewire\Admin\Cotizaciones;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Opciones;
use App\Livewire\Admin\ProyectoFormulario;
use App\Livewire\Admin\ProyectosIndex;
use App\Livewire\Admin\Sistemas;
use App\Livewire\Admin\Tarifas;
use App\Livewire\Cotizador;
use App\Livewire\Galeria;
use App\Livewire\Inicio;
use App\Livewire\Login;
use App\Livewire\ProyectoDetalle;
use App\Livewire\Proyectos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Web pública
|--------------------------------------------------------------------------
*/
Route::get('/', Inicio::class)->name('inicio');
Route::get('/proyectos', Proyectos::class)->name('proyectos');
Route::get('/proyectos/{proyecto:slug}', ProyectoDetalle::class)->name('proyectos.ver');
Route::get('/galeria', Galeria::class)->name('galeria');

/*
|--------------------------------------------------------------------------
| 2. Sistema de cotización
|--------------------------------------------------------------------------
*/
Route::get('/cotizar', Cotizador::class)->name('cotizar');

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
    Route::get('/sistemas', Sistemas::class)->name('sistemas');
    Route::get('/tarifas', Tarifas::class)->name('tarifas');
    Route::get('/opciones', Opciones::class)->name('opciones');
    Route::get('/cotizaciones', Cotizaciones::class)->name('cotizaciones');
});
