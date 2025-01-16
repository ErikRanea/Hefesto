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
        Schema::create('registros_trabajo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_ticket');
            $table->date('fecha_trabajo');
            $table->decimal('horas_trabajo', 10, 2);
            $table->boolean('habilitado')->default(true);
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_ticket')->references('id')->on('tickets')->onDelete('cascade');

            $table->unique(['id_usuario', 'id_ticket', 'fecha_trabajo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_trabajo');
    }
};
