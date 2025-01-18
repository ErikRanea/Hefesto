<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenimientoPreventivo extends Model
{
    //
    protected $fillable = ['nombre', 'descripcion', 'periodicidad', 'id_maquina', 'fecha_inicio_proximo_mant','estado'];


    public function maquina()
    {
        return $this->belongsTo(Maquina::class, 'id_maquina', 'id_maquina');
    }

     public function incidencias()
     {
        return $this->hasMany(Incidencia::class, 'id_mantenimiento_preventivo', 'id_mantenimiento');
    }
}
