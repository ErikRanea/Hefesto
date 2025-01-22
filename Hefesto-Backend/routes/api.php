<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoIncidenciaController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\TecnicoIncidenciaController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MantenimientoPreventivoController;
use App\Http\Controllers\ImageController;



Route::prefix('v1')->group(function () {

    Route::prefix('main')->group(function () {
        Route::get('carga-inicial', [MainController::class, 'cargaInicial']);
    });


    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        //Route::post('register', [AuthController::class, 'register']);
        Route::middleware('auth:api')->group(function () {
           Route::post('register', [AuthController::class, 'register'])->middleware('admin');
            Route::post('register_tecnico', [AuthController::class, 'registerTecnico'])->middleware('admin');
            Route::get('me', [AuthController::class, 'me']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('validate-token', [AuthController::class, 'validateToken']); 
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        });
    });



    Route::prefix('image')->group(function () {
        Route::post('upload', [ImageController::class, 'upload']);
    });

    Route::prefix('incidencia')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('all', [IncidenciaController::class, 'all']);
            Route::get('show/{id}', [IncidenciaController::class, 'show']);
            Route::post('store', [IncidenciaController::class, 'store'])->middleware('tecnico');
            Route::put('update_estado/{incidencia}', [IncidenciaController::class, 'update_estado'])->middleware('tecnico');
            Route::delete('delete/{id}', [IncidenciaController::class, 'delete'])->middleware('admin');
            Route::put('update_description/{id}', [IncidenciaController::class, 'updateDescription'])->middleware('tecnico');
        });
    });
    
    Route::prefix('tecnico_incidencia')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('reclamar_incidencia/', [TecnicoIncidenciaController::class, 'reclamarIncidencia'])->middleware('tecnico');
            Route::put('salir_incidencia/', [TecnicoIncidenciaController::class, 'salirIncidencia'])->middleware('tecnico');
            Route::put('cerrar_incidencia/', [TecnicoIncidenciaController::class, 'cerrarIncidencia'])->middleware('tecnico');
        });
    });


    Route::middleware('auth:api')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::prefix('campus')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('store', [CampusController::class, 'store'])->middleware('admin');
            Route::get('all',[CampusController::class, 'all']);
            Route::get('show/{campus}',[CampusController::class, 'show']);
            Route::put('update/{campus}',[CampusController::class, 'update'])->middleware('admin');
            Route::delete('delete/{campus}',[CampusController::class,'delete'])->middleware('admin');
        });
    });

    Route::prefix('seccion')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('store', [SeccionController::class, 'store'])->middleware('admin');
            Route::post('all',[SeccionController::class, 'all']);
            Route::get('show/{seccion}',[SeccionController::class, 'show']);
            Route::put('update/{seccion}',[SeccionController::class, 'update'])->middleware('admin');
            Route::delete('delete/{seccion}',[SeccionController::class,'delete'])->middleware('admin');
        });
    }); 

    Route::prefix('maquina')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('store', [MaquinaController::class, 'store'])->middleware('admin');
            Route::post('all',[MaquinaController::class, 'all']);
            Route::get('show/{maquina}',[MaquinaController::class, 'show']);
            Route::put('update/{maquina}',[MaquinaController::class, 'update'])->middleware('admin');
            Route::delete('delete/{maquina}',[MaquinaController::class,'delete'])->middleware('admin');
        });
    });

    Route::prefix('mantenimiento-preventivo')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('create', [MantenimientoPreventivoController::class, 'create'])->middleware('admin');
            Route::post('store', [MantenimientoPreventivoController::class, 'store'])->middleware('admin');
            Route::get('all',[MantenimientoPreventivoController::class, 'all']);
            Route::get('show/{mantenimiento_preventivo}',[MantenimientoPreventivoController::class, 'show']);
            Route::put('update/{mantenimiento_preventivo}',[MantenimientoPreventivoController::class, 'update'])->middleware('admin');
            Route::delete('delete/{mantenimiento_preventivo}',[MantenimientoPreventivoController::class,'delete'])->middleware('admin');
        });
    });


    Route::prefix('tipo_incidencia')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('create', [TipoIncidenciaController::class, 'create'])->middleware('admin');
            Route::post('store', [TipoIncidenciaController::class, 'store'])->middleware('admin');
            Route::get('all',[TipoIncidenciaController::class, 'all']);
            Route::get('show/{tipo_incidencia}',[TipoIncidenciaController::class, 'show']);
            Route::put('update/{tipo_incidencia}',[TipoIncidenciaController::class, 'update'])->middleware('admin');
            Route::delete('delete/{tipo_incidencia}',[TipoIncidenciaController::class,'delete'])->middleware('admin');
        });
    });

    Route::prefix('usuario')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::get('all',[UserController::class, 'all'])->middleware('admin');
            Route::get('show/{usuario}',[UserController::class, 'show'])->middleware('admin');
            Route::put('update/{usuario}',[UserController::class, 'update'])->middleware('admin');
            Route::delete('delete/{usuario}',[UserController::class,'delete'])->middleware('admin');
        });
    });

});

Route::get('/', [AuthController::class, 'unauthorized'])->name('login');