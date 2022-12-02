<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/create_user', [AuthController::class, 'createUser']);

Route::group(['middleware' => 'auth:sanctum'], function () { 
    Route::prefix('master')->group(function () {
        Route::get('/index', [MasterController::class, 'index']);
        Route::post('/create', [MasterController::class, 'create']);
        Route::post('/detail/{id}', [MasterController::class, 'detail']);
        Route::post('/update/{id}', [MasterController::class, 'update']);
        Route::post('/delete/{id}', [MasterController::class, 'delete']);
    });

});


