<?php

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\Authorization;
use App\Http\Controllers\Api\V1\ContactController;
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
            Route::any('/{id}/retrieve', 'RetrieveUser');
            Route::any('password/update', 'UpdatePassword');
        });
    });
    Route::any('authorization', [Authorization::class, 'Authorization'])->name('authorization');

    ###========== CONTACT ROUTINGS ========###
    Route::controller(ContactController::class)->group(function () {
        Route::prefix('contact')->group(function () {
            Route::any('add', 'ContactCreate'); #AJOUT DE CONTACT
            Route::any('all', 'Contacts'); #GET ALL CONTACTS
            Route::any('{id}/retrieve', 'ContactRetrieve'); #RECUPERATION D'UN CONTACT
            Route::any('{id}/delete', 'DeleteContact'); #SUPPRESSION DE CONTACT
        });
    });
});
