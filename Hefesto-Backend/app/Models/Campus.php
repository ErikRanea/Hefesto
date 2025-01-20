<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Campus extends Model
{
    //

    protected $fillable = ['nombre_campus', 'habilitado'];


   public function secciones() 
   {
       return $this->hasMany(Seccion::class, 'id_campus', 'id_campus');
   }




}
