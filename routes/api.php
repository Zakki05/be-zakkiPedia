<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\OngkirController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PemesananDetailController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukDetailController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['api'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [UserController::class, 'sendResetLinkEmail']);
        Route::post('/reset-password', [UserController::class, 'resetPassword']);
        Route::middleware(['auth:api'])->group(function () {
            Route::post('/validateToken', [AuthController::class, 'validateToken']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::put('/changePassword',[UserController::class,'changePassword']);
            Route::apiResource('/role', RoleController::class)->except(['edit', 'create']);
            Route::apiResource('/user', UserController::class)->except(['edit', 'create']);
            Route::apiResource('/kategori', KategoriController::class)->except(['edit', 'create']);
            Route::apiResource('/ongkir', OngkirController::class)->except(['edit', 'create']);
            Route::apiResource('/pelanggan', PelangganController::class)->except(['edit', 'create']);
            Route::apiResource('/pembayaran', PembayaranController::class)->except(['edit', 'create']);
            Route::apiResource('/pemesanan', PemesananController::class)->except(['edit', 'create']);
            Route::apiResource('/pemesananDetail', PemesananDetailController::class)->except(['edit', 'create']);
            Route::apiResource('/produk', ProdukController::class)->except(['edit', 'create']);
            Route::apiResource('/produkDetail', ProdukDetailController::class)->except(['edit', 'create']);

            Route::middleware(['superAdmin'])->group(function(){
                Route::post('/storePelanggan', [UserController::class, 'storePelanggan']);
            });

            Route::middleware(['admin'])->group(function(){

            });

            Route::middleware(['pelanggan'])->group(function(){

            });
        });
    });
});
