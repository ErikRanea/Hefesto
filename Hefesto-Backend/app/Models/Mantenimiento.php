<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    //
    protected $fillable = [
        'fecha_apertura',
        'descripcion',
        'estado',
        'id_usuario_reporta',
        'id_maquina',
        'fecha_cierre',
        'id_tipo_incidencia',
       'id_mantenimiento_preventivo'
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

    public function tecnicosMantenimientos()
    {
        return $this->hasMany(TecnicoMantenimiento::class, 'id_mantenimiento', 'id');
    }


}
