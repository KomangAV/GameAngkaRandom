<?php

use App\Http\Controllers\GameController;

Route::get('/', [GameController::class, 'index']);
Route::post('/guess', [GameController::class, 'guess'])->name('guess');
Route::get('/reset', [GameController::class, 'reset'])->name('reset');
Route::post('/hint', [GameController::class, 'hint'])->name('hint');
Route::get('/set-locale', [GameController::class, 'setLocale'])->name('set.locale');



