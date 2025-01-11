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
        Schema::create('seccion', function (Blueprint $table) {
            $table->bigIncrements('id_seccion');
            $table->string('nombre_seccion');
            $table->unsignedBigInteger('id_campus');
            $table->boolean('habilitado')->default(true);
            $table->timestamps();

            $table->foreign('id_campus')->references('id_campus')->on('campus');
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
