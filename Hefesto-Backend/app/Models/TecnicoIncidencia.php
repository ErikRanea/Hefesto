<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TecnicoIncidencia extends Model
{
    //
    protected $fillable = [
        'id_incidencia',
        'id_tecnico',
        'fecha_entrada',
        'fecha_salida',
        'motivo_salida',
        'estado_tecnico',
        'tiempo_trabajado'

    ];


    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class, 'id_incidencia', 'id_incidencia');
    }

}
