<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('zone_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('antenne_id')->constrained('antennes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('zone_stocks');
    }
};

