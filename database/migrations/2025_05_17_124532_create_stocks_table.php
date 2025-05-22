<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('zone_stock_id')->constrained('zones_stocks')->onDelete('cascade');
            $table->unsignedSmallInteger('quantite');
            $table->date('date_peremption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stocks');
    }
};
