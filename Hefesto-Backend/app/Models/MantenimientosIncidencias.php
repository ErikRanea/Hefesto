<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenimientosIncidencias extends Model
{
    //

    protected $fillable = [
        'id_incidencia',
        'id_mantenimiento',
        'fecha_realizacion',
        'fecha_proximo_mantenimiento'
    ];


    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class, 'id', 'id_incidencia');
    }

    public function mantemiento()
    {
        return $this->belongsTo(MantenimientoPreventivo::class, 'id', 'id_mantenimiento');
    }

    
}
