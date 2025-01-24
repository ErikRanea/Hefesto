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
        Schema::create('mantenimientos_incindencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_incidencia');
            $table->unsignedBigInteger('id_mantenimiento');
            $table->date('fecha_realizacion')->nullable();
            $table->date('fecha_proximo_mantenimiento')->nullable();
            $table->foreign('id_incidencia')->references('id')->on('incidencias');
            $table->foreign('id_mantenimiento')->references('id')->on('mantenimientos_preventivos');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimientos_incindencias');
    }
};
