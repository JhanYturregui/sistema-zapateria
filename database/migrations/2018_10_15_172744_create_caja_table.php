<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->double('monto_apertura', 8, 2);
            $table->double('monto_cierre', 8, 2)->nullable();
            $table->double('monto_real', 8, 2)->nullable();
            $table->string('comentario')->nullable();
            $table->integer('usuario_apertura');
            $table->integer('usuario_cierre')->nullable();
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
        Schema::dropIfExists('caja');
    }
}
