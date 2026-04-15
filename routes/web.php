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


Route::get('/debug-db', function () {
    try {
        // Force le passage des migrations
        Artisan::call('migrate', ['--force' => true]);
        return "Succès : " . Artisan::output();
    } catch (\Exception $e) {
        return "Erreur lors de la migration : " . $e->getMessage();
    }
});
Route::get('/force-init', function () {
    try {
        // migrate:fresh supprime tout et recrée tout
        Artisan::call('migrate:fresh', ['--force' => true]);
        return "Base de données réinitialisée avec succès !";
    } catch (\Exception $e) {
        return "Erreur : " . $e->getMessage();
    }
});