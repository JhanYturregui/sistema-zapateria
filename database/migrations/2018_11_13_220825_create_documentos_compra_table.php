<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_compra', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->integer('usuario');
            $table->string('proveedor');
            $table->json('productos');
            $table->json('cantidades');
            $table->json('descuentos');
            $table->double('monto_total', 7, 2);
            $table->integer('usuario_anulacion')->nullable();
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
        Schema::dropIfExists('documentos_compra');
    }
}
