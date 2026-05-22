<?php

use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::options('{any}', function() {
    return response()->json([], 200);
})->where('any', '.*');

Route::get('videos',                [VideoController::class, 'index']);
Route::get('videos/{id}',           [VideoController::class, 'show']);
Route::post('videos/upload',        [VideoController::class, 'upload']);
Route::delete('videos/{id}',        [VideoController::class, 'destroy']);
Route::get('videos/{id}/download',  [VideoController::class, 'download']);