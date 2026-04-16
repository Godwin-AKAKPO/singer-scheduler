<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membres', function (Blueprint $table) {
            // Remplacer lead par lead_c1 + lead_c2
            $table->boolean('lead_c1')->default(false)->after('cultes_autorises');
            $table->boolean('lead_c2')->default(false)->after('lead_c1');
            $table->dropColumn('lead');

            // Renommer choeur_p1/p2/p3
            $table->renameColumn('choeur_p1', 'choeur_sopra');
            $table->renameColumn('choeur_p2', 'choeur_alto');
            $table->renameColumn('choeur_p3', 'choeur_tenor');
        });
    }

    public function down(): void
    {
        Schema::table('membres', function (Blueprint $table) {
            $table->boolean('lead')->default(false)->after('cultes_autorises');
            $table->dropColumn(['lead_c1', 'lead_c2']);
            $table->renameColumn('choeur_sopra', 'choeur_p1');
            $table->renameColumn('choeur_alto', 'choeur_p2');
            $table->renameColumn('choeur_tenor', 'choeur_p3');
        });
    }
};