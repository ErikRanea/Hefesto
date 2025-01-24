<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    //

    protected $fillable = ['nombre_maquina', 'numero_interno', 'tipo_maquina', 'prioridad', 
    'habilitado'];


    public function seccion()
    {
        return $this->belongsTo(Seccion::class,'id_seccion');
    }

    public function incidencias()
    {
       return $this->hasMany(Incidencia::class, 'id_maquina');
    }

    public function mantenimientosMaquinas(){
        return $this->hasMany(MantenimientoMaquina::class, 'id_incidencia','id');
    }
}
