<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    //
    protected $table = 'secciones';
    protected $fillable = ['nombre_seccion', 'habilitado'];


    public function campus() 
    {
        return $this->belongsTo(Campus::class, 'id_campus', 'id_campus');
    }

    public function maquinas(){
        return $this->hasMany(Maquina::class, 'id_seccion', 'id_seccion');
    }


}
