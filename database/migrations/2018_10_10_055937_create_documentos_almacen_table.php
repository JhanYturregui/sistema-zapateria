<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosAlmacenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_almacen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->string('origen');
            $table->string('destino');
            $table->integer('usuario');
            $table->json('productos');
            $table->json('cantidades');
            $table->json('tallas');
            $table->json('cantidad_talla');
            $table->json('tallas')->nullable();
            $table->json('cantidad_talla')->nullable();
            $table->string('comentario')->nullable();
            $table->integer('usuario_anulacion')->nullable();
            $table->integer('estado');
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
        Schema::dropIfExists('documentos_almacen');
    }
}
