<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\CursusController;
use Illuminate\Support\Facades\Route;

// ─── COURSES ─────────────────────────────────────
Route::get('courses',               [CourseController::class, 'index']);
Route::get('courses/filter',        [CourseController::class, 'filter']);
Route::get('courses/{id}',          [CourseController::class, 'show']);
Route::post('courses',              [CourseController::class, 'store']);
Route::put('courses/{id}',          [CourseController::class, 'update']);
Route::post('courses/{id}/publish', [CourseController::class, 'publish']);
Route::delete('courses/{id}',       [CourseController::class, 'destroy']);

// ─── FORMATIONS ──────────────────────────────────
Route::get('formations',                                      [FormationController::class, 'index']);
Route::get('formations/{id}',                                 [FormationController::class, 'show']);
Route::post('formations',                                     [FormationController::class, 'store']);
Route::post('formations/{id}/chapter',                        [FormationController::class, 'addChapter']);
Route::post('formations/{formationId}/chapter/{chapterId}/course', [FormationController::class, 'addCourseToChapter']);
Route::delete('formations/{id}',                              [FormationController::class, 'destroy']);

// ─── CURSUS ──────────────────────────────────────
Route::get('cursus',         [CursusController::class, 'index']);
Route::get('cursus/{id}',    [CursusController::class, 'show']);
Route::post('cursus',        [CursusController::class, 'store']);
Route::put('cursus/{id}',    [CursusController::class, 'update']);
Route::delete('cursus/{id}', [CursusController::class, 'destroy']);

// ─── PROGRESSION ─────────────────────────────────
Route::post('progression/watch',                      [\App\Http\Controllers\ProgressionController::class, 'markAsWatched']);
Route::get('progression/user/{userId}',               [\App\Http\Controllers\ProgressionController::class, 'getUserProgress']);
Route::get('progression/user/{userId}/formation/{formationId}', [\App\Http\Controllers\ProgressionController::class, 'getFormationProgress']);
Route::get('progression/stats/course/{courseId}',     [\App\Http\Controllers\ProgressionController::class, 'getCourseStats']);
Route::get('progression/stats/formation/{formationId}', [\App\Http\Controllers\ProgressionController::class, 'getFormationStats']);

// ─── RECHERCHE ───────────────────────────────────
Route::get('search', [\App\Http\Controllers\SearchController::class, 'search']);
Route::post('courses/{id}/toggle-premium', [CourseController::class, 'togglePremium']);
Route::post('formations/{id}/toggle-premium', [FormationController::class, 'togglePremium']);