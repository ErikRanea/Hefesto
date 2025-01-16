<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquina extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'maquinas';

    /**
     * @var string
     */
    protected $primaryKey = 'id_maquina';

    /**
     * @var bool
     */
     public $timestamps = true;


    /**
     * @var list<string>
     */
    protected $fillable = [
        'nombre_maquina',
        'numero_interno',
        'tipo_maquina',
        'id_campus',
        'id_seccion',
        'prioridad',
        'habilitado',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'habilitado' => 'boolean',
        ];
    }
}