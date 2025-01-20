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
        Schema::create('tecnicos_mantenimientos', function (Blueprint $table) {
            $table->id();; // Identificador único de la entrada en la relación
            $table->unsignedBigInteger('id_mantenimiento'); // Identificador del mantenimiento
            $table->unsignedBigInteger('id_tecnico'); // Identificador del técnico
            $table->dateTime('fecha_entrada'); // Fecha y hora en que el técnico se unió al mantenimineto
            $table->dateTime('fecha_salida')->nullable(); // Fecha y hora en que el técnico salió del mantenimiento
            $table->text('motivo_salida')->nullable(); // Justificación de la salida del técnico
             $table->enum('estado_tecnico', ['activo','inactivo']); // Estado del técnico en la incidencia
             $table->integer('tiempo_trabajado')->nullable(); // Tiempo total trabajado por el técnico en la incidencia (en segundos)

            $table->foreign('id_mantenimiento')->references('id')->on('mantenimientos');
            $table->foreign('id_tecnico')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnicos_mantenimientos');
    }
};
