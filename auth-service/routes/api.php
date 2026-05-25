<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbonnementController;

// Réponse aux requêtes OPTIONS (preflight CORS)
Route::options('{any}', function() {
    return response()->json([], 200);
})->where('any', '.*');

// Routes publiques
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// Routes protégées JWT
Route::prefix('auth')->middleware('jwt')->group(function () {
    Route::get ('me',               [AuthController::class, 'me']);
    Route::post('logout',           [AuthController::class, 'logout']);
    Route::post('change-role/{id}', [AuthController::class, 'changeRole']);
});

// Route pour lister tous les users (admin)
Route::get('users', function() {
    return response()->json(\App\Models\User::all());
})->middleware('jwt');


// ─── ABONNEMENTS ─────────────────────────────────
Route::post('abonnements/souscrire',    [AbonnementController::class, 'souscrire']);
Route::get('abonnements/statut/{userId}', [AbonnementController::class, 'statut']);
Route::post('abonnements/annuler/{userId}', [AbonnementController::class, 'annuler']);
Route::get('abonnements',               [AbonnementController::class, 'index']);