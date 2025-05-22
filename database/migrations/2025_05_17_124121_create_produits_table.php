<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('types_produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('desc')->nullable();
            $table->boolean('perissable')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('produits');
    }
};
