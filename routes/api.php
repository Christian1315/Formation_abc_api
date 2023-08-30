<?php

use App\Http\Controllers\Api\V1\TransportController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\Authorization;
use App\Http\Controllers\Api\V1\FretController;
use App\Http\Controllers\Api\V1\TransportType;
use App\Http\Controllers\Api\V1\Notifications;
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
        Route::any('register', 'Register');
        Route::any('login', 'Login');
        Route::middleware(['auth:api'])->get('logout', 'Logout');
        Route::any('users','Users');
        Route::any('users/{id}','RetrieveUser');
    });
    
    Route::any('authorization',[Authorization::class,'Authorization'])->name('authorization');

    ###========== TRANSPORTs ROUTINGS========###
    Route::prefix('transports')->group(function () {
        Route::controller(TransportController::class)->group(function () {
            Route::any('/create', 'Create');
            Route::any('/all', '_Transports'); #RECUPERER TOUTS LES MOYENS DE TRANSPORT
            Route::any('/{id}/retrieve', 'Retrieve'); #RECUPERER UN SEUL MOYENS DE TRANSPORT
            Route::any('/{id}/update', 'Update');#MODIFIER UN SEUL MOYEN DE TRANSPORT
            Route::any('/{id}/delete', 'Delete');#SUPPRIMER UN MOYEN DE TRANSPORT
        });

        Route::prefix('types')->group(function () {
            Route::controller(TransportType::class)->group(function () {
                Route::any('/create', 'Create');#CREER UN TYPE DE MOYEN DE TRANSPORT
                Route::any('all', 'transportTypes'); #RECUPERER TOUTS LES TYPES DE MOYENS DE TRANSPORT
    
                Route::any('/{id}/retrieve', 'Retrieve'); #RECUPERER UN SEUL TYPE DE MOYENS DE TRANSPORT
                Route::any('/{id}/update', 'Update');#MODIFIER UN TYPE DE MOYEN DE TRANSPORT
                Route::any('/{id}/delete', 'Delete');#SUPPRIMER UN TYPE DE MOYEN DE TRANSPORT
    
                Route::any('/search', 'Search');#RECHERCHER UN TYPE DE MOYEN DE TRANSPORT
            });
        });
    });

    ###========== FREts ROUTINGS========###
    Route::prefix('frets')->group(function () {
        Route::controller(FretController::class)->group(function () {
            Route::any('/create', 'Create');
            Route::any('/', 'ForAll'); #RECUPERER TOUTS LES FRETS
            Route::any('user/{id}', 'ForUser'); #RECUPERER TOUTS LES FRETS D'UN USER
            Route::any('user/{id}/validated', 'ValidatedForUser'); #RECUPERER TOUTS LES FRETS VALIDES D'UN USER

            Route::any('/{id}/retrieve', 'Retrieve'); #RECUPERER UN SEUL FRET
            Route::any('/{id}/update', 'Update');#MODIFIER UN FRET
            Route::any('/{id}/delete', 'Delete');#SUPPRIMER UN FRET
        });
    });

    Route::prefix('notifications')->group(function(){
        Route::controller(Notifications::class)->group(function(){
            Route::any('','_AllNotifications');#RECUPERER TOUTES LES NOTIFICATIONS
            Route::any('/create','Create');#CREER UNE NOTIFICATION
            Route::any('/{id}/notification','Retrieve');#RECUPERER UNE NOTIFICATION VIA SON **id**
            Route::any('/{id}/retreive','NotificationsReceived');#RECUPERER TOUTES LES NOTIFICATION RECU POUR UN USER VIA SON **RECEIVER_ID**
            Route::any('/{id}/update', 'Update');#MODIFIER UNE NOTIFICATION
            Route::any('/{id}/delete','Delete');#SUPPRESSION D'UNE NOTIFICATION
        });
    });
});
