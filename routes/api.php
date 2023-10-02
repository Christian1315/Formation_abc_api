<?php

use App\Http\Controllers\Api\V1\TransportController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\Authorization;
use App\Http\Controllers\Api\V1\FactureController;
use App\Http\Controllers\Api\V1\FretController;
use App\Http\Controllers\Api\V1\TransportType;
use App\Http\Controllers\Api\V1\Notifications;
use App\Http\Controllers\Api\V1\TransportStatusController;
use App\Http\Controllers\Api\V1\FretStatusController;
use App\Http\Controllers\Api\V1\MarchandiseTypeController;
use App\Http\Controllers\Api\V1\ReservationController;
use App\Http\Controllers\Api\V1\MarchandiseController;
use App\Http\Controllers\Api\V1\RoleController;
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


Route::prefix('v1')->group(function () {
    ###========== USERs ROUTINGS ========###
    Route::controller(UserController::class)->group(function () {
        Route::prefix('user')->group(function () {
            Route::any('register', 'Register');
            Route::any('login', 'Login');
            Route::middleware(['auth:api'])->get('logout', 'Logout');
            Route::any('all', 'Users');
            Route::any('password/update', 'UpdatePassword');
            Route::any('/{id}/retrieve', 'RetrieveUser');
        });
    });

    Route::any('authorization', [Authorization::class, 'Authorization'])->name('authorization');
});
