<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenimientoPreventivo extends Model
{
    //
    protected $table = 'mantenimientos_preventivos';
    protected $fillable = [
        'nombre',
        'descripcion', 
        'periodicidad', 
        'estado'
    ];

    public function mantenimientosMaquinas(){
        return $this->hasMany(MantenimientoMaquina::class, 'id_incidencia','id');
    }

}
