<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\laborsController;
use App\Http\Controllers\SeedSowingController;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route::middleware(['auth.jwt'])->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/profile', [AuthController::class, 'profile']);
// });

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/me', [AuthController::class, 'me']);


    // ========================== Labors  APIs ========================= //

    Route::post('/add-labor', [laborsController::class, 'add']);
    Route::put('/edit-labor', [laborsController::class, 'edit']);
    Route::get('/labor-pagination-list', [laborsController::class, 'paginationlist']);
    Route::get('/labor-details/{id}', [laborsController::class, 'details']);
    Route::put('/update-labor-status', [laborsController::class, 'updateStatus']);

    // ========================== Seed Sowing  APIs ========================= //

    Route::post('/add-seed-sowing', [SeedSowingController::class, 'add']);
    Route::put('/edit-seed-sowing', [SeedSowingController::class, 'edit']);
    Route::get('/seed-sowing-pagination-list', [SeedSowingController::class, 'paginationlist']);
    Route::get('/seed-sowing-details/{id}', [SeedSowingController::class, 'details']);
    Route::put('/update-seed-sowing-status', [SeedSowingController::class, 'updateStatus']);
});
