<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemApiController;
use App\Http\Controllers\Api\AuthController;

// Route default - berguna untuk mendapatkan informasi user yang login
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Tambahkan routes API Lost and Found Items di sini
Route::prefix('v1')->group(function () {
    // Route untuk path dasar /api/v1
    Route::get('/', function () {
        return response()->json([
            'name' => 'Lost and Found Items API',
            'version' => '1.0.0',
            'endpoints' => [
                'GET /api/v1/items' => 'Daftar semua barang',
                'GET /api/v1/items/{id}' => 'Detail barang',
                'GET /api/v1/search' => 'Pencarian barang',
                'POST /api/v1/login' => 'Login user',
                'POST /api/v1/items' => 'Tambah barang (auth required)',
                'PUT /api/v1/items/{id}' => 'Update barang (auth required)',
                'DELETE /api/v1/items/{id}' => 'Hapus barang (auth required)',
            ]
        ]);
    });

    Route::get('/test', function () {
        return response()->json(['message' => 'Test works!']);
    });

    // Authentication
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    // Public endpoints
    Route::get('/items', [ItemApiController::class, 'index']);
    Route::get('/items/{item}', [ItemApiController::class, 'show']);
    Route::get('/search', [ItemApiController::class, 'search']);

    // Protected endpoints
    Route::middleware('auth:sanctum')->group(function () {
        // Items CRUD
        Route::post('/items', [ItemApiController::class, 'store']);
        Route::put('/items/{id}', [ItemApiController::class, 'update']);
        Route::delete('/items/{item}', [ItemApiController::class, 'destroy']);

        // Claims
        Route::post('/items/{item}/claim', [ItemApiController::class, 'claim']);
        Route::post('/items/{item}/return', [ItemApiController::class, 'returnItem']);
    });
});
