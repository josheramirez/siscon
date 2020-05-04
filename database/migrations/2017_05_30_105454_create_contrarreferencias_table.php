<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContrarreferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrarreferencias', function (Blueprint $table) {
            $table->increments('id')->index();
			$table->integer('paciente_id')->unsigned();
			$table->integer('estContra_id')->unsigned();
			$table->integer('estOrigen_id')->unsigned();
			$table->boolean('primera_consulta');
			$table->integer('diagEgreso_id')->unsigned();
			$table->text('tratamiento');
			$table->text('indicaciones_aps')->nullable();
			$table->string('observaciones')->nullable();;
			$table->integer('ges_id')->unsigned()->nullable();;
			$table->integer('protocolo_id')->unsigned();
			$table->integer('tiempo_id')->unsigned();
			$table->smallInteger('tipo_salida');
			$table->smallInteger('control_aps')->nullable();
			$table->smallInteger('control_especialidad')->nullable();;
			$table->string('detalle_examenes')->nullable();;
			$table->integer('especialidad_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string('nombre_usuario');
			$table->smallInteger('causal_egreso')->nullable();
			$table->date('fecha_agendamiento')->nullable();			
            $table->timestamps();
			
			$table->foreign('paciente_id')->references('id')->on('pacientes')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('estContra_id')->references('id')->on('establecimientos')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('estOrigen_id')->references('id')->on('establecimientos')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('diagEgreso_id')->references('id')->on('cie10s')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('ges_id')->references('id')->on('tipo_ges')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('protocolo_id')->references('id')->on('protocolos')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('tiempo_id')->references('id')->on('protocolos')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('especialidad_id')->references('id')->on('especialidads')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('contrarreferencias');
    }
}
