<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\BloqueController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\PiezaController;
use App\Http\Controllers\ReporteController; // <-- Añade esta línea para importar el ReporteController
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// 🔒 Redirige a login si acceden a la raíz
Route::get('/', function () {
    return redirect('/login');
});

// 📊 Ruta al Dashboard principal (protegido)
Route::middleware(['auth', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 👤 Rutas para editar perfil (protegidas)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 📦 CRUD de Proyectos (protegido)
    Route::prefix('proyectos')->name('proyectos.')->group(function () {
        Route::get('/', [ProyectoController::class, 'index'])->name('index');
        Route::post('/', [ProyectoController::class, 'store'])->name('store');
        Route::put('/{proyecto}', [ProyectoController::class, 'update'])->name('update');
        Route::delete('/{proyecto}', [ProyectoController::class, 'destroy'])->name('destroy');
    });

    // 🧱 CRUD de Bloques (protegido)
    Route::prefix('bloques')->name('bloques.')->group(function () {
        Route::get('/', [BloqueController::class, 'index'])->name('index');
        Route::post('/', [BloqueController::class, 'store'])->name('store');
        Route::put('/{bloque}', [BloqueController::class, 'update'])->name('update');
        Route::delete('/{bloque}', [BloqueController::class, 'destroy'])->name('destroy');
    });

    // 📝 CRUD de Registros (protegido)
    Route::prefix('registros')->name('registros.')->group(function () {
        Route::get('/', [RegistroController::class, 'index'])->name('index');
        Route::post('/', [RegistroController::class, 'store'])->name('store');
        Route::put('/{registro}', [RegistroController::class, 'update'])->name('update');
        Route::delete('/{registro}', [RegistroController::class, 'destroy'])->name('destroy');
    });

    // ⚙️ CRUD de Piezas (protegido)
    Route::prefix('piezas')->name('piezas.')->group(function () {
        Route::get('/', [PiezaController::class, 'index'])->name('index');
        Route::post('/', [PiezaController::class, 'store'])->name('store');
        Route::put('/{pieza}', [PiezaController::class, 'update'])->name('update');
        Route::delete('/{pieza}', [PiezaController::class, 'destroy'])->name('destroy');
    });

    // 📈 Rutas de Reportes (protegidas) <-- Añade este bloque
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/piezas-por-proyecto', [ReporteController::class, 'exportProyectosPiezas'])->name('piezas_por_proyecto');
    });
});

// 🔐 Rutas de autenticación generadas por Breeze/Fortify
require __DIR__.'/auth.php';