<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Incidencia;
use App\Models\TecnicoIncidencia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class TecnicoIncidenciaController extends Controller
{
    //


    public function reclamarIncidencia(Request $request)
    {

        try {
            
            $validator = Validator::make($request->all(), [
                'id_incidencia' => 'required|int',
            ]);     

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $incidencia = Incidencia::find($request->get('id_incidencia'));
            $idtecnico = auth()->user()->id;

            $tecnicoIncidencia = TecnicoIncidencia::where('id_tecnico', $idtecnico)
                ->whereNull('fecha_salida')
                ->first();


            /*
            En caso de que el técnico ya tenga una incidencia asignada, no podrá reclamar más incidencias
            */

            if ($tecnicoIncidencia != null) {
                return response()->json(['error' => 'No puedes reclamar más incidencias.'], Response::HTTP_BAD_REQUEST);
            }
            


            $tecnicoIncidencia = new TecnicoIncidencia();
            $tecnicoIncidencia->id_tecnico = $idtecnico;
            $tecnicoIncidencia->id_incidencia = $incidencia->id;
            $tecnicoIncidencia->fecha_entrada = Date::now();
            $tecnicoIncidencia->estado_tecnico = 'activo';
            $tecnicoIncidencia->save();

            IncidenciaController::estadoEnCurso($incidencia);

            return response()->json(['message' => 'Incidencia asignada con éxito!', 'data' => $tecnicoIncidencia], Response::HTTP_CREATED);
        }
        catch (Exception $e) {
            return response()->json([
                'error' => 'Error al asignar la incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function salirIncidencia(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_incidencia' => 'required|int',
                'motivo_salida' => 'required|string',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $idtecnico = auth()->user()->id;
            $tecnicoIncidencia = TecnicoIncidencia::where('id_incidencia', $request->get('id_incidencia'))->where('id_tecnico', $idtecnico)
            ->where('fecha_salida', null)->first();

            if($tecnicoIncidencia == null){
                return response()->json(['error' => 'No se ha encontrado la incidencia.'], Response::HTTP_NOT_FOUND);
            }

            $tecnicoIncidencia->fecha_salida = Date::now();
            $tecnicoIncidencia->motivo_salida = $request->get('motivo_salida');
            $tecnicoIncidencia->estado_tecnico = 'inactivo';
            $tecnicoIncidencia->tiempo_trabajado = Carbon::parse($tecnicoIncidencia->fecha_entrada)->diffInMinutes($tecnicoIncidencia->fecha_salida);
            $tecnicoIncidencia->save();

            $incidencia = Incidencia::find($request->get('id_incidencia'));

            IncidenciaController::estadoEnEspera($incidencia);

            return response()->json(['message' => 'Incidencia desasignada con exitó!', 'data' => $tecnicoIncidencia], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al salir de la incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function cerrarIncidencia(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'id_incidencia' => 'required|int',
                'motivo_salida' => 'required|string'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $idtecnico = auth()->user()->id;
            /*
                Priemro cierro la actividad del técnico que envía la petición y luego con un foreach reviso si tengo que cerrar
                más actividades
            */
          
            
            // Cerrar la actividad del técnico que envía la petición
            $tecnicoIncidencia = TecnicoIncidencia::where('id_incidencia', $request->get('id_incidencia'))
                ->where('id_tecnico', $idtecnico)
                ->whereNull('fecha_salida')
                ->first();

            if ($tecnicoIncidencia) {
                $tecnicoIncidencia->fecha_salida = Carbon::now();
                $tecnicoIncidencia->motivo_salida = $request->get('motivo_salida');
                $tecnicoIncidencia->estado_tecnico = 'inactivo';
                $tecnicoIncidencia->tiempo_trabajado = Carbon::parse($tecnicoIncidencia->fecha_entrada)->diffInMinutes($tecnicoIncidencia->fecha_salida);
                $tecnicoIncidencia->save();
            }

            // Revisar si hay más actividades con el mismo id_incidencia donde fecha_salida no esté rellenada
            $otrasIncidencias = TecnicoIncidencia::where('id_incidencia', $request->get('id_incidencia'))
                ->whereNull('fecha_salida')
                ->get();

            foreach ($otrasIncidencias as $incidencia) {
                $incidencia->fecha_salida = Carbon::now();
                $incidencia->motivo_salida = $request->get('motivo_salida');
                $incidencia->estado_tecnico = 'inactivo';
                $incidencia->tiempo_trabajado = Carbon::parse($incidencia->fecha_entrada)->diffInMinutes($incidencia->fecha_salida);
                $incidencia->save();
            }


          

            $incidencia = Incidencia::find($request->get('id_incidencia'));
            IncidenciaController::estadoCerrado($incidencia);
            
         

            return response()->json(['message' => 'Incidencia cerrada con éxito!', 'data' => $tecnicoIncidencia], Response::HTTP_OK);

        } 
        catch (Exception $e) {
            return response()->json([
                'error' => 'aad',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }


    public function getIncidenciasAsignadas()
    {
        try {
            $idtecnico = auth()->user()->id;
            $incidencias = TecnicoIncidencia::where('id_tecnico', $idtecnico)
                ->whereNull('fecha_salida')
                ->get();

            return response()->json(['data' => $incidencias], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al obtener las incidencias asignadas.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
