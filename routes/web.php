<?php

use App\Http\Controllers\AmountMoneyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RouletteController;
use App\Http\Controllers\RouletteStatisticController;
use App\Http\Controllers\UserController;
use App\Models\RouletteOptions;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'redirect']);
Route::get('/login', [LoginController::class, 'show']);
Route::post('/auth/login', [LoginController::class, 'login'])->name('auth.login');

 
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');    
Route::get('/ruleta', [RouletteController::class, 'show'])->name('ruleta');
Route::post('/ruleta/guardarResultado', [RouletteController::class, 'guardarResultado']);
Route::group(['middleware' => 'admin'], function() {
    Route::get('/opciones/ruleta', [RouletteController::class, 'opcionesRuleta']);
    Route::post('/opciones/ruleta', [RouletteController::class, 'store']);
    Route::put('/opciones/ruleta/{id}', [RouletteController::class, 'update']);
    Route::delete('/opciones/ruleta/{id}', [RouletteController::class, 'delete']);
    Route::resource('/opciones/cantidad', AmountMoneyController::class);
    Route::get('/opciones/estadisticas', [RouletteStatisticController::class, 'getStatistics']);
    Route::delete('/opciones/estadisticas/delete', [RouletteStatisticController::class, 'deleteStatistics']);
    Route::get('/opciones/estadisticas/filtros', [RouletteStatisticController::class, 'getStatistics']);
    Route::resource('/opciones/usuarios', UserController::class);
});
