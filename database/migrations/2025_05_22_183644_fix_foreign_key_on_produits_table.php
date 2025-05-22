<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropForeign(['type_produit_id']); // Supprime la mauvaise contrainte
            $table->foreign('type_produit_id')
                ->references('id')
                ->on('types_produits')
                ->onDelete('cascade'); // Ajoute la bonne
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
