<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    //

    protected $fillable = [
        'fecha_apertura',
        'descripcion',
        'estado',
        'id_usuario_reporta',
        'id_maquina',
        'fecha_cierre',
        'prioridad',
        'tipo_incidencia',
       'id_mantenimiento_preventivo'
    ];



    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario_reporta', 'id_usuario');
    }

     public function maquina()
    {
        return $this->belongsTo(Maquina::class, 'id_maquina', 'id_maquina');
    }


     public function mantenimientoPreventivo()
    {
        return $this->belongsTo(MantenimientoPreventivo::class, 'id_mantenimiento_preventivo', 'id_mantenimiento');
    }

        /**
     * Get all of the tecnicos_incidencias for the Incidencia.
     */
    public function tecnicosIncidencias()
    {
        return $this->hasMany(TecnicoIncidencia::class, 'id_incidencia', 'id_incidencia');
    }
    
}
