<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->string('modelo');
            $table->string('descripcion')->nullable();
            $table->integer('marca');
            $table->integer('color');
            $table->integer('linea');
            $table->integer('linea_2');
            $table->integer('linea_3');
            $table->integer('taco')->nullable();
            $table->double('precio_compra', 7, 2)->nullable();
            $table->double('precio_venta', 7, 2);
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
        Schema::dropIfExists('productos');
    }
}
