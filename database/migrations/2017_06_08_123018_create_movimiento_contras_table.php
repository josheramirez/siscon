<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientoContrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_contras', function (Blueprint $table) {
            $table->increments('id')->index();
			$table->integer('contrarref_id')->unsigned();
			$table->string('estado');
			$table->integer('user_id')->unsigned();
			$table->boolean('active')->default(true);
            $table->timestamps();
			
			$table->foreign('contrarref_id')->references('id')->on('contrarreferencias')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimiento_contras');
    }
}
