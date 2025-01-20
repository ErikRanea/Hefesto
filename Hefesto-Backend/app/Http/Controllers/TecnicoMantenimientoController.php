<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\MantenimientoPreventivo;
use Illuminate\Support\Facades\Date;
use App\Models\TecnicoMantenimiento;
use Illuminate\Http\Response;
use Exception;

class TecnicoMantenimientoController extends Controller
{
    //

    public function reclamarMantenimiento(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'id_mantenimiento' => 'required|int',
            ]);     

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $mantenimiento = Mantenimiento::findorFail($request->get('id_mantenimiento'));
            $idtecnico = auth()->user()->id;
            $tecnicoMantenimiento = new TecnicoMantenimiento();
            $tecnicoMantenimiento->id_tecnico = $idtecnico;
            $tecnicoMantenimiento->id_mantenimiento = $mantenimiento->id;
            $tecnicoMantenimiento->fecha_entrada = Date::now();
            $tecnicoMantenimiento->estado_tecnico = 'activo';
            $tecnicoMantenimiento->save();

            MantenimientoController::estadoEnCurso($mantenimiento);

            return response()->json(['message' => 'Incidencia asignada con éxito!', 'data' => $tecnicoMantenimiento], Response::HTTP_CREATED);
        }
        catch (Exception $e) {
            return response()->json([
                'error' => 'Error al asignar el mantenimiento.',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);}
    }

    public function salirMantenimiento(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_mantenimiento' => 'required|int',
                'motivo_salida' => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $idtecnico = auth()->user()->id;
            $tecnicoMantenimiento = TecnicoMantenimiento::where('id_tecnico', $idtecnico)->where('id_mantenimiento', 
            $request->get('id_mantenimiento'))->first();
            
            if($tecnicoMantenimiento == null)
            {
                return response()->json(['error' => 'No se encontró la mantenimiento asignada al técnico.'], Response::HTTP_NOT_FOUND);
            }

            $tecnicoMantenimiento->fecha_salida = Date::now();
            $tecnicoMantenimiento->motivo_salida = $request->get('motivo_salida');
            $tecnicoMantenimiento->estado_tecnico = 'inactivo';
            $tecnicoMantenimiento->tiempo_trabajado = Carbon::parse($tecnicoMantenimiento->fecha_entrada)->diffInMinutes($tecnicoMantenimiento->fecha_salida);
            $tecnicoMantenimiento->save();

            $mantenimiento = Mantenimiento::find($request->get('id_mantenimiento'));

            MantenimientoController::estadoEnEspera($mantenimiento);

            return response()->json(['message' => 'Incidencia desasignada con éxito!'], Response::HTTP_OK);

        } 
        catch (Exception $e) {
            return response()->json(['error' => 'Error al desasignar la mantenimiento.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function cerrarMantenimiento(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id_mantenimiento' => 'required|int',
                'motivo_salida' => 'required|string'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $idtecnico = auth()->user()->id;
            /*
                Primero cierro la actividad del técnico que envía la petición y luego con un foreach reviso si tengo que cerrar
                más actividades
            */
            
            // Cerrar la actividad del técnico que envía la petición
            $tecnicoMantenimiento = TecnicoMantenimiento::where('id_mantenimiento', $request->get('id_mantenimiento'))
                ->where('id_tecnico', $idtecnico)
                ->whereNull('fecha_salida')
                ->first();

            if ($tecnicoMantenimiento) {
                $tecnicoMantenimiento->fecha_salida = Carbon::now();
                $tecnicoMantenimiento->motivo_salida = $request->get('motivo_salida');
                $tecnicoMantenimiento->estado_tecnico = 'inactivo';
                $tecnicoMantenimiento->tiempo_trabajado = Carbon::parse($tecnicoMantenimiento->fecha_entrada)->diffInMinutes($tecnicoMantenimiento->fecha_salida);
                $tecnicoMantenimiento->save();
            }

            // Revisar si hay más actividades con el mismo id_mantenimiento donde fecha_salida no esté rellenada
            $otrosMantenimientos = TecnicoMantenimiento::where('id_mantenimiento', $request->get('id_mantenimiento'))
                ->whereNull('fecha_salida')
                ->get();

            foreach ($otrosMantenimientos as $mantenimiento) {
                $mantenimiento->fecha_salida = Carbon::now();
                $mantenimiento->motivo_salida = $request->get('motivo_salida');
                $mantenimiento->estado_tecnico = 'inactivo';
                $mantenimiento->tiempo_trabajado = Carbon::parse($mantenimiento->fecha_entrada)->diffInMinutes($mantenimiento->fecha_salida);
                $mantenimiento->save();
            }

            $mantenimiento = Mantenimiento::find($request->get('id_mantenimiento'));
            MantenimientoController::estadoCerrado($mantenimiento);
            
            return response()->json(['message' => 'mantenimiento cerrada con éxito!', 'data' => $tecnicoMantenimiento], Response::HTTP_OK);

        } 
        catch (Exception $e) {
            return response()->json([
                'error' => 'Error al cerrar la mantenimiento.',
                'message'=> $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




}
