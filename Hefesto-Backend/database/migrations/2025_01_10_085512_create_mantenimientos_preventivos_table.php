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
        Schema::create('mantenimientos_preventivos', function (Blueprint $table) {
            $table->id();// Identificador único del mantenimiento preventivo
            $table->string('nombre'); // Nombre del mantenimiento preventivo
            $table->text('descripcion')->nullable(); // Descripción del mantenimiento preventivo
            $table->integer('periodicidad'); // Periodicidad del mantenimiento preventivo
            $table->unsignedBigInteger('id_maquina'); // Identificador de la máquina a la que pertenece
            $table->date('fecha_ultimo_mantenimiento'); // Fecha del último mantenimiento
            $table->boolean('habilitado')->default(true);
            $table->enum('estado', ['pendiente', 'en curso', 'completado'])->default('pendiente'); // Estado del mantenimiento preventivo
            $table->foreign('id_maquina')->references('id')->on('maquinas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimientos_preventivos');
    }
};
