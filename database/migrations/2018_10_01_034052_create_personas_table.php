<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_documento');
            $table->string('numero_documento');
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->json('roles')->nullable();
            $table->boolean('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
