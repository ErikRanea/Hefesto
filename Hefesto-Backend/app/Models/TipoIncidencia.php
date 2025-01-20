<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoIncidencia extends Model
{
    //

    protected $table = 'tipos_incidencia';
    protected $fillable = ['tipo', 'prioridad'];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }
}
