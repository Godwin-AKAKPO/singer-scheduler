<?php

use App\Http\Controllers\MembreController;
use App\Http\Controllers\SessionMensuelleController;
use App\Http\Controllers\ProgrammationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Route::get('/', fn() => redirect()->route('membres.index'));

Route::resource('membres', MembreController::class);
Route::resource('sessions', SessionMensuelleController::class);

Route::post('sessions/{session}/generer',  [ProgrammationController::class, 'generer'])->name('programmation.generer');
Route::put('sessions/{session}/modifier',  [ProgrammationController::class, 'modifier'])->name('programmation.modifier');
Route::get('sessions/{session}/pdf',       [ProgrammationController::class, 'exportPdf'])->name('programmation.pdf');

Route::get('/init-db-force', function () {
    try {
        // 1. Force la fermeture de toute transaction corrompue et nettoie le schéma
        // CASCADE supprime tout (tables, index, contraintes) sans poser de questions
        DB::statement('DROP SCHEMA public CASCADE;');
        DB::statement('CREATE SCHEMA public;');
        DB::statement('GRANT ALL ON SCHEMA public TO public;');

        // 2. Relance les migrations proprement
        Artisan::call('migrate', ['--force' => true]);

        return "Succès ! La base est nettoyée et les tables sont créées. <br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return " Erreur : " . $e->getMessage();
    }
});