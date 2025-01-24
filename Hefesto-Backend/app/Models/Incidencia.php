<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    //

    protected $fillable = [
        'fecha_apertura',
        'titulo',
        'subtitulo',
        'descripcion',
        'estado',
        'id_usuario_reporta',
        'id_maquina',
        'fecha_cierre',
        'prioridad',
        'id_tipo_incidencia'
    ];



    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario_reporta', 'id');
    }

     public function maquina()
    {
        return $this->belongsTo(Maquina::class, 'id_maquina', 'id');
    }


     public function mantenimientoPreventivo()
    {
        return $this->belongsTo(MantenimientoPreventivo::class, 'id_mantenimiento_preventivo', 'id');
    }

        /**
     * Get all of the tecnicos_incidencias for the Incidencia.
     */

    public function tipoIncidencia()
    {
        return $this->belongsTo(TipoIncidencia::class, 'id_tipo_incidencia', 'id');
    }
    
    public function tecnicosIncidencias()
    {
        return $this->hasMany(TecnicoIncidencia::class, 'id_incidencia', 'id');
    }

    public function mantenimientosIncidencias(){
        return $this->hasMany(MantenimientosIncidencias::class, 'id_incidencia','id');
    }



}
