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
        Schema::create('secciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_seccion');
            $table->unsignedBigInteger('id_campus');
            $table->boolean('habilitado')->default(true);
            $table->timestamps();

            $table->foreign('id_campus')->references('id')->on('campuses');
            $table->unique(['nombre_seccion', 'id_campus']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccion');
    }
};
