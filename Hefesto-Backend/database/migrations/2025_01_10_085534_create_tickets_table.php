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
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('tipo_ticket', ['incidencia', 'mantenimiento']);
            $table->unsignedBigInteger('id_maquina');
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_cierre')->nullable();
            $table->integer('prioridad_averia')->nullable();
            $table->string('tipo_averia')->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['pendiente', 'en curso', 'en espera', 'finalizado']);
            $table->unsignedBigInteger('usuario_creacion');
            $table->decimal('horas_trabajo_total', 10, 2)->default(0.00);
            $table->string('periodicidad')->nullable();
            $table->dateTime('fecha_ultima_ejecucion')->nullable();
            $table->boolean('habilitado')->default(true);
            $table->timestamps();

            $table->foreign('id_maquina')->references('id_maquina')->on('maquinas');
            $table->foreign('usuario_creacion')->references('id')->on('users');

            $table->index('id_maquina');
            $table->index('prioridad_averia');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};