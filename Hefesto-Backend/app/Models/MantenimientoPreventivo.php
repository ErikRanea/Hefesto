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
        'id_maquina', 
        'fecha_ultimo_mantenimiento',
        'estado'
    ];


    public function maquina()
    {
        return $this->belongsTo(Maquina::class, 'id_maquina', 'id_maquina');
    }

     public function mantenimiento()
     {
        return $this->hasMany(Mantenimiento::class, 'id_mantenimiento_preventivo', 'id_mantenimiento');
    }

    public function tecnicosMantenimientos()
    {
        return $this->hasMany(TecnicoMantenimiento::class, 'id_incidencia', 'id');
    }

}
