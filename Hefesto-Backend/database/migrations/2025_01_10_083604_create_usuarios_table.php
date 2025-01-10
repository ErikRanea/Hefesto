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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id_usuario');
            $table->string('nombre_usuario');
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('rol', ['operario', 'tecnico', 'administrador']);
            $table->string('foto_perfil')->nullable();
            $table->boolean('habilitado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
