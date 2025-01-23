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
use Illuminate\Support\Facades\Log;


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
           
           Log::info("Buscando asignación de técnico a la incidencia:");
            Log::info("ID de incidencia recibido: " . $request->get('id_incidencia'));
            Log::info("ID de técnico autenticado: " . $idtecnico);

            // Obtener la consulta para inspeccionarla
            $query = TecnicoIncidencia::where('id_incidencia', $request->get('id_incidencia'))
                ->where('id_tecnico', $idtecnico)
                ->where('estado_tecnico', 'activo');
            
             $sql =  $query->toSql();
              Log::info("SQL Query: " . $sql);
              Log::info("SQL Bindings: " . json_encode($query->getBindings()));
            // Buscar la asignación del técnico a la incidencia
            $tecnicoIncidencia = $query->first(); // Usamos first() para obtener un modelo
            
            Log::info("Técnico Incidencia (Resultado de la query): " . json_encode($tecnicoIncidencia));
           
            if (!$tecnicoIncidencia) {
                Log::warning("No se encontró la incidencia activa para este técnico");
                return response()->json(['error' => 'No se ha encontrado la incidencia activa para este técnico.'], Response::HTTP_NOT_FOUND);
            }
            
            // Actualizar la información de la asignación del técnico
           /* $tecnicoIncidencia->fecha_salida = Date::now();
             $tecnicoIncidencia->motivo_salida = $request->get('motivo_salida');
            $tecnicoIncidencia->estado_tecnico = 'inactivo';
             $tecnicoIncidencia->tiempo_trabajado = Carbon::parse($tecnicoIncidencia->fecha_entrada)->diffInMinutes($tecnicoIncidencia->fecha_salida);
            $tecnicoIncidencia->save();*/

            
           // Verificar si hay otros técnicos activos en la misma incidencia
             $hayGente = TecnicoIncidencia::where('id_incidencia', $request->get('id_incidencia'))
                ->where('estado_tecnico', 'activo')
                ->exists(); //Usamos exists para saber si hay o no hay otros técnicos, no necesito el registro en sí.
            Log::info("Hay gente: " . ($hayGente ? 'true' : 'false'));

            //Si no hay gente, cambia el estado de la incidencia a en espera
             if (!$hayGente) {
                $incidencia = Incidencia::find($request->get('id_incidencia'));
                if ($incidencia) {
                    IncidenciaController::estadoEnEspera($incidencia);
                     Log::info("Incidencia con id " . $request->get('id_incidencia') . 'cambiada a en espera.');
                } else {
                     Log::warning("No se encontró la incidencia con id " . $request->get('id_incidencia') . '.');
                }
            }


          return response()->json(['message' => 'Incidencia desasignada con éxito!', 'data' => $tecnicoIncidencia], Response::HTTP_OK);

        } catch (Exception $e) {
              Log::error("Error al salir de la incidencia: " . $e->getMessage());
             return response()->json([
                 'error' => 'Error al salir de la incidencia.',
                  'data' =>  $e->getMessage()
                  ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getAllTecnicoIncidencias()
    {
        try {
            $tecnicoIncidencias = TecnicoIncidencia::all();
            return response()->json(['data' => $tecnicoIncidencias], Response::HTTP_OK);
        } catch (Exception $e) {
             return response()->json([
                'error' => 'Error al obtener todos los TecnicoIncidencia.',
                'message' => $e->getMessage(),
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

    public function reclamarIncidenciaMultiple(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'id_incidencia' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $idtecnico = auth()->user()->id;

        // Verificar si el técnico ya tiene una incidencia en curso
        $tecnicoIncidenciaActiva = TecnicoIncidencia::where('id_tecnico', $idtecnico)
            ->where('estado_tecnico', 'activo')
            ->first();

        if ($tecnicoIncidenciaActiva) {
            return response()->json(['error' => 'Ya tienes una incidencia en curso asignada. Debes salir de esa incidencia para poder reclamar otra.'], Response::HTTP_BAD_REQUEST);
        }


        $incidencia = Incidencia::find($request->get('id_incidencia'));


        $tecnicoIncidencia = new TecnicoIncidencia();
        $tecnicoIncidencia->id_tecnico = $idtecnico;
        $tecnicoIncidencia->id_incidencia = $incidencia->id;
        $tecnicoIncidencia->fecha_entrada = Date::now();
        $tecnicoIncidencia->estado_tecnico = 'activo';
        $tecnicoIncidencia->save();

         if($incidencia->estado != 2){
            IncidenciaController::estadoEnCurso($incidencia);
         }


        return response()->json(['message' => 'Incidencia asignada con éxito!', 'data' => $tecnicoIncidencia], Response::HTTP_CREATED);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Error al asignar la incidencia.'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}


}
