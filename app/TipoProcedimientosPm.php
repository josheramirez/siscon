<?php

namespace siscont;

use Illuminate\Database\Eloquent\Model;

/**
 * Clase Modelo TipoProcedimientoPM - Administra datos de Tipo de ProcedimientoPM
 */
class TipoProcedimientosPm extends Model
{   
    /**
     * Funcion que retorna TipoProcedimientos Asociados a Procedimientos_PMS
     *
     * @return void clase TipoProcedimiento
     */
    public function tipoprocedimientospm()
    {
        return $this->hasMany('siscont\TipoProcedimiento');
    }
}
