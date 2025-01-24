<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Campus extends Model
{
    //

    protected $table = 'campuses';
    protected $fillable = ['nombre_campus', 'habilitado'];


   public function secciones() 
   {
       return $this->hasMany(Seccion::class, 'id_campus', 'id_campus');
   }

   public function users(){
    return $this->hasMany(User::class,'id_campus');
   }




}
