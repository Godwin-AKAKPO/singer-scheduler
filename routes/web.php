<?php

use App\Http\Controllers\MembreController;
use App\Http\Controllers\SessionMensuelleController;
use App\Http\Controllers\ProgrammationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('membres.index');
});

Route::resource('membres', MembreController::class);
Route::resource('sessions', SessionMensuelleController::class);
Route::post('sessions/{session}/generer', [ProgrammationController::class, 'generer'])->name('programmation.generer');