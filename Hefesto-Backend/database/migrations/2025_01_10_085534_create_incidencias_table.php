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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();; // Identificador único de la incidencia
            $table->dateTime('fecha_apertura'); // Fecha y hora en que se reportó la incidencia
            $table->text('descripcion'); // Descripción detallada de la incidencia
            $table->enum('estado', ['abierta', 'cerrada']); // Estado actual de la incidencia
            $table->unsignedBigInteger('id_usuario_reporta')->nullable(); // Identificador del usuario que reportó la incidencia (puede ser null)
            $table->unsignedBigInteger('id_maquina'); // Identificador de la máquina relacionada
            $table->dateTime('fecha_cierre')->nullable(); // Fecha y hora en que se cerró la incidencia (nullable)
            $table->enum('prioridad', ['baja', 'media', 'alta']); // Prioridad de la incidencia
            $table->enum('tipo_incidencia', ['correctiva','preventiva']); // Tipo de incidencia
            $table->unsignedBigInteger('id_mantenimiento_preventivo')->nullable(); // Identificador del mantenimiento preventivo relacionado (nullable)
            $table->foreign('id_usuario_reporta')->references('id')->on('users');
            $table->foreign('id_maquina')->references('id')->on('maquinas');
            $table->foreign('id_mantenimiento_preventivo')->references('id')->on('mantenimientos_preventivos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};