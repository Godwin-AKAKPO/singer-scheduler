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
        Schema::create('membres', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->json('cultes_autorises'); // ["C1","C2"] ou ["C2"]
                $table->boolean('lead')->default(false);
                $table->boolean('choeur_p1')->default(false);
                $table->boolean('choeur_p2')->default(false);
                $table->boolean('choeur_p3')->default(false);
                $table->boolean('piano1')->default(false);
                $table->boolean('piano2')->default(false);
                $table->boolean('solo')->default(false);
                $table->boolean('basse')->default(false);
                $table->boolean('batterie')->default(false);
                $table->integer('score')->default(5); // 1 à 10
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
