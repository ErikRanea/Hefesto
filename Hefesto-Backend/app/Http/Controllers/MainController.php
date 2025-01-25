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
 
            $campus = new Campus();
            $campus->nombre_campus = 'Jesus Obrero';
            $campus->habilitado = true;
            $campus->save();

            $seccion = new Seccion();
            $seccion->nombre_seccion = 'MA12';
            $seccion->id_campus = $campus->id;
            $seccion->habilitado = true;
            $seccion->save();

            $seccion = new Seccion();
            $seccion->nombre_seccion = 'MA13';
            $seccion->id_campus = $campus->id;
            $seccion->habilitado = true;
            $seccion->save();


            $maquina = new Maquina();
            $maquina->nombre_maquina = 'Fresadora';
            $maquina->id_seccion = $seccion->id;
            $maquina->numero_interno = 2134132;
            $maquina->tipo_maquina = 'Industrial';
            $maquina->prioridad = 3;
            $maquina->habilitado = true;
            $maquina->save();


            $campus = new Campus();
            $campus->nombre_campus = 'Arriaga';
            $campus->habilitado = true;
            $campus->save();


            $seccion = new Seccion();
            $seccion->nombre_seccion = '142GA';
            $seccion->id_campus = $campus->id;
            $seccion->habilitado = true;
            $seccion->save();

            $seccion = new Seccion();
            $seccion->nombre_seccion = '141GA';
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
            


            UserController::createDummyUsers();



            $user = new User();
            $user->name = 'Hefesto';
            $user->primer_apellido = 'Admin';
            $user->segundo_apellido = 'Hefesto';
            $user->email = 'hefesto@hefesto.com';
            $user->password = Hash::make('adminhefesto');
            $user->rol = 'administrador';
            $user->habilitado = true;
            $user->id_campus = 1;
            $user->save();

            $user = new User();
            $user->name = 'ibai';
            $user->primer_apellido = 'tecnico';
            $user->segundo_apellido = 'Hefesto';
            $user->email = 'i@i.com';
            $user->password = Hash::make('tecnicoibai');
            $user->rol = 'tecnico';
            $user->habilitado = true;
            $user->id_campus = 2;
            $user->save();

            $user = new User();
            $user->name = 'erik';
            $user->primer_apellido = 'erik';
            $user->segundo_apellido = 'Hefesto';
            $user->email = 'e@e.com';
            $user->password = Hash::make('tecnicoerik');
            $user->rol = 'tecnico';
            $user->habilitado = true;
            $user->id_campus = 2;
            $user->save();

        
          

            TipoIncidenciaController::crearTipos();

            $mantenimiento = new MantenimientoPreventivo();
            $mantenimiento->nombre = 'Se engrasa la maquina';
            $mantenimiento->descripcion = 'Mantenimiento preventivo';
            $mantenimiento->periodicidad = 1;
            $mantenimiento->save();

            IncidenciaController::cargarIncidencias();

            $incidencia = new Incidencia();
            $incidencia->titulo = 'Engrasar Maquina';
            $incidencia->subtitulo = 'Hay que engrasar la máquina';
            $incidencia->id_maquina = $maquina->id;
            $incidencia->id_tipo_incidencia = 7;
            $incidencia->estado = 4;
            $incidencia->prioridad = 'baja';
            $incidencia->computo_prioridad=2;
            $incidencia->id_creador = $user->id;
            $incidencia->id_mantenimiento = 1;
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