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
        Schema::create('mantenimientos_maquinas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_maquina');
            $table->unsignedBigInteger('id_mantenimiento');
            $table->date('fecha_proximo');
            $table->date('fecha_realizacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimientos_maquinas');
    }
};
