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
            $table->text('titulo'); // Título de la incidencia
            $table->text('subtitulo')->nullable(); // Subtítulo de la incidencia (nullable)
            $table->text('descripcion')->nullable(); // Descripción detallada de la incidencia
            /**
             * 0-> nuevo
             * 1-> pediente
             * 2-> en curso
             * 3-> cerrado
             * 4-> mantenimiento
             */
            $table->integer('estado')->default(0); // Estado actual de la incidencia
            $table->unsignedBigInteger('id_creador')->nullable(); // Identificador del usuario que reportó la incidencia (puede ser null)
            $table->unsignedBigInteger('id_maquina'); // Identificador de la máquina relacionada
            $table->dateTime('fecha_cierre')->nullable(); // Fecha y hora en que se cerró la incidencia (nullable)
            $table->boolean('habilitado')->default(true);
            $table->unsignedBigInteger('id_tipo_incidencia'); // Tipo de incidencia
            $table->unsignedBigInteger('id_mantenimiento')->nullable();
            $table->foreign('id_creador')->references('id')->on('users');
            $table->foreign('id_maquina')->references('id')->on('maquinas');
            $table->foreign('id_tipo_incidencia')->references('id')->on('tipos_incidencia');
            $table->foreign('id_mantenimiento')->references('id')->on('mantenimientos_preventivos');
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