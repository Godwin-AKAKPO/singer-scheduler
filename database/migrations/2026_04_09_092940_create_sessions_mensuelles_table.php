<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sessions_mensuelles', function (Blueprint $table) {
            $table->id();
            $table->integer('annee');
            $table->integer('mois'); // 1 à 12
            $table->jsonb('absences')->nullable(); // [{nom, dates}]
            $table->jsonb('programmation')->nullable(); // résultat généré
            $table->timestamps();

            $table->unique(['annee', 'mois']); // un seul planning par mois
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions_mensuelles');
    }
};
