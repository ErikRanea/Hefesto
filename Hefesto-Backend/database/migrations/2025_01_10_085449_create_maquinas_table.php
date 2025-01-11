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
        Schema::create('maquinas', function (Blueprint $table) {
            $table->bigIncrements('id_maquina');
            $table->string('nombre_maquina');
            $table->string('numero_interno');
            $table->string('tipo_maquina')->nullable();
            $table->unsignedBigInteger('id_campus')->nullable();
            $table->unsignedBigInteger('id_seccion')->nullable();
            $table->boolean('habilitado')->default(true);
            $table->timestamps();

            $table->foreign('id_campus')->references('id_campus')->on('campus');
            $table->foreign('id_seccion')->references('id_seccion')->on('seccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maquinas');
    }
};
