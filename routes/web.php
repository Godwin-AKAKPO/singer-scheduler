<?php

use App\Http\Controllers\MembreController;
use App\Http\Controllers\SessionMensuelleController;
use App\Http\Controllers\ProgrammationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
Route::get('/', fn() => redirect()->route('membres.index'));

Route::resource('membres', MembreController::class);
Route::resource('sessions', SessionMensuelleController::class);

Route::post('sessions/{session}/generer',  [ProgrammationController::class, 'generer'])->name('programmation.generer');
Route::put('sessions/{session}/modifier',  [ProgrammationController::class, 'modifier'])->name('programmation.modifier');
Route::get('sessions/{session}/pdf',       [ProgrammationController::class, 'exportPdf'])->name('programmation.pdf');


Route::get('/repare-moi-ca', function () {
    try {
        // Le --force est obligatoire en production pour migrate:fresh
        Artisan::call('migrate:fresh', [
            '--force' => true,
        ]);
        return " Succès ! Les tables ont été recréées : <br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return " Erreur : " . $e->getMessage();
    }
});