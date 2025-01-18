<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
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
    Route::prefix('ticket')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::get('all', [IncidenciaController::class, 'index']);
            Route::get('show/{id}', [IncidenciaController::class, 'show']);
            Route::post('store', [IncidenciaController::class, 'store']);
            Route::put('update/{id}', [IncidenciaController::class, 'update']);
            Route::delete('delete/{id}', [IncidenciaController::class, 'destroy']);
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
            Route::get('all',[SeccionController::class, 'all']);
            Route::get('show/{seccion}',[SeccionController::class, 'show']);
            Route::put('update/{seccion}',[SeccionController::class, 'update'])->middleware('admin');
            Route::delete('delete/{seccion}',[SeccionController::class,'delete'])->middleware('admin');
        });
    }); 

    Route::prefix('maquina')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('store', [MaquinaController::class, 'store'])->middleware('admin');
            Route::get('all',[MaquinaController::class, 'all']);
            Route::get('show/{maquina}',[MaquinaController::class, 'show']);
            Route::put('update/{maquina}',[MaquinaController::class, 'update'])->middleware('admin');
            Route::delete('delete/{maquina}',[MaquinaController::class,'delete'])->middleware('admin');
        });
    });


});

Route::get('/', [AuthController::class, 'unauthorized'])->name('login');