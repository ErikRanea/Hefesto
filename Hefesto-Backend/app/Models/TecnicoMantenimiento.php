<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TecnicoMantenimiento extends Model
{
    //
    protected $table = 'tecnicos_mantenimientos';
    protected $fillable = [
        'id_mantenimiento',
        'id_tecnico',
        'fecha_entrada',
        'fecha_salida',
        'motivo_salida',
        'estado_tecnico',
        'tiempo_trabajado'

    ];

    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class, 'id', 'id_mantenimiento');
    }




}