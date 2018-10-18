<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_venta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->integer('usuario');
            $table->string('cliente');
            $table->json('productos');
            $table->json('cantidades');
            $table->double('monto_total', 7, 2);
            $table->json('modos_pago');
            $table->json('montos');
            $table->string('numero_caja');
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
        Schema::dropIfExists('documentos_venta');
    }
}
