<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMedicoToContrarreferencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrarreferencias', function($table) {
            $table->string('nombre_medico')->after('nombre_usuario')->nullable();
            $table->integer('rut_medico')->after('nombre_medico')->nullable();
            $table->char('dv_medico',1)->after('rut_medico')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrarreferencias', function($table) {
            $table->dropColumn('nombre_medico');
            $table->dropColumn('rut_medico');
            $table->dropColumn('dv_medico');
        });
    }
}
