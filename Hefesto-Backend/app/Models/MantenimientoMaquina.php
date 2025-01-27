<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenimientoMaquina extends Model
{
    //

    protected $table = 'mantenimientos_maquinas';
    protected $fillable = [
        'id_mantenimiento',
        'id_maquina',
        'fecha_realizacion',
        'fecha_proximo'
    ];

    public function maquina(){
        return $this->belongsTo(Maquina::class, 'id','id_maquina');
    }

    public function mantenimiento()
    {
        return $this->belongsTo(MantenimientoPreventivo::class, 'id', 'id_mantenimiento');
    }
}
