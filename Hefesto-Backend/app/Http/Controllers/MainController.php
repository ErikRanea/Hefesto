<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Campus;
use App\Models\Seccion;
use App\Models\Maquina;
use App\Models\Incidencia;
use App\Models\MantenimientoPreventivo;
use App\Models\TipoIncidencia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Exception;

class MainController extends Controller
{
    public function cargaInicial()
    {
        try {
 

            $user = new User();
            $user->name = 'Hefesto';
            $user->primer_apellido = 'Admin';
            $user->segundo_apellido = 'Hefesto';
            $user->email = 'hefesto@hefesto.com';
            $user->password = Hash::make('adminhefesto');
            $user->rol = 'administrador';
            $user->habilitado = true;
            $user->save();

        
            $campus = new Campus();
            $campus->nombre_campus = 'Arriaga';
            $campus->habilitado = true;
            $campus->save();

            $seccion = new Seccion();
            $seccion->nombre_seccion = '142GA';
            $seccion->id_campus = $campus->id;
            $seccion->habilitado = true;
            $seccion->save();

            $maquina = new Maquina();
            $maquina->nombre_maquina = 'Torno CNC';
            $maquina->id_seccion = $seccion->id;
            $maquina->numero_interno = 12345;
            $maquina->tipo_maquina = 'Industrial';
            $maquina->prioridad = 1;
            $maquina->habilitado = true;
            $maquina->save();

            TipoIncidenciaController::crearTipos();

            $mantenimiento = new MantenimientoPreventivo();
            $mantenimiento->nombre = 'Mantenimiento preventivo';
            $mantenimiento->descripcion = 'Mantenimiento preventivo';
            $mantenimiento->id_maquina = $maquina->id;
            $mantenimiento->periodicidad = 1;
            $mantenimiento->fecha_ultimo_mantenimiento = now();
            $mantenimiento->save();

            $incidencia = new Incidencia();
            $incidencia->titulo = 'Máquina no arranca';
            $incidencia->subtitulo = 'Máquina no arranca ibai';
            $incidencia->descripcion = 'La máquina no arranca';
            $incidencia->id_maquina = $maquina->id;
            $incidencia->id_tipo_incidencia = 10;
            $incidencia->estado = 0;
            $incidencia->id_creador = $user->id;
            $incidencia->fecha_apertura = now();
            $incidencia->save();

            

            return response()->json(['message' => 'Carga inicial completada con éxito!'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al realizar la carga inicial.',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}