<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbonnementController;
use Illuminate\Support\Facades\Route;

// ─── CORS preflight ───────────────────────────────
Route::options('{any}', function() {
    return response()->json([], 200);
})->where('any', '.*');

// ─── AUTH publiques ───────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// ─── AUTH protégées ───────────────────────────────
Route::prefix('auth')->middleware('jwt')->group(function () {
    Route::get ('me',               [AuthController::class, 'me']);
    Route::post('logout',           [AuthController::class, 'logout']);
    Route::post('change-role/{id}', [AuthController::class, 'changeRole']);
});

// ─── USERS ───────────────────────────────────────
Route::get('users', function() {
    return response()->json(\App\Models\User::all());
})->middleware('jwt');

// ─── ABONNEMENTS ─────────────────────────────────
Route::post('abonnements/souscrire',        [AbonnementController::class, 'souscrire']);
Route::get('abonnements/statut/{userId}',   [AbonnementController::class, 'statut']);
Route::post('abonnements/annuler/{userId}', [AbonnementController::class, 'annuler']);
Route::get('abonnements',                   [AbonnementController::class, 'index']);