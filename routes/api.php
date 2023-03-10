<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NodesController;
use App\Http\Controllers\RoutesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('/nodes')->group(function(){
    Route::put('/create', [NodesController::class, 'create']);
    Route::post('/edit', [NodesController::class, 'edit']);
    Route::delete('/delete', [NodesController::class, 'delete']);
    Route::get('/list', [NodesController::class, 'list']);
});
Route::prefix('/routes')->group(function(){
    Route::put('/create', [RoutesController::class, 'create']);
    Route::post('/edit', [RoutesController::class, 'edit']);
    Route::delete('/delete', [RoutesController::class, 'delete']);
    Route::get('/list', [RoutesController::class, 'list']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
