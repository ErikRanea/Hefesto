<?php

namespace App\Http\Controllers;
use App\Models\TipoIncidencia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Exception;

class TipoIncidenciaController extends Controller
{
    //


    public function create(){
        $tiposIncidencia = [
            ['tipo' => 'Fallo Mecánico', 'prioridad' => 3],
            ['tipo' => 'Fallo Eléctrico', 'prioridad' => 3],
            ['tipo' => 'Mantenimiento Preventivo', 'prioridad' => 1],
            ['tipo' => 'Mantenimiento Correctivo', 'prioridad' => 2],
            ['tipo' => 'Reparación Urgente', 'prioridad' => 3],
            ['tipo' => 'Ajuste de Máquina', 'prioridad' => 1],
            ['tipo' => 'Lubricación', 'prioridad' => 1],
            ['tipo' => 'Reemplazo de Piezas', 'prioridad' => 2],
            ['tipo' => 'Inspección de Seguridad', 'prioridad' => 2],
            ['tipo' => 'Actualización de Software', 'prioridad' => 1],
            ['tipo' => 'Calibración', 'prioridad' => 2],
            ['tipo' => 'Parada de Emergencia', 'prioridad' => 3],
            ['tipo' => 'Problema de Calidad', 'prioridad' => 2],
            ['tipo' => 'Fallo de Sensor', 'prioridad' => 3],
            ['tipo' => 'Desgaste de Herramienta', 'prioridad' => 2],
        ];

        foreach ($tiposIncidencia as $tipo) {
            $tipoIncidencia = new TipoIncidencia();
            $tipoIncidencia->tipo = $tipo['tipo'];
            $tipoIncidencia->prioridad = $tipo['prioridad'];
            $tipoIncidencia->save();
        }

        return response()->json(['message' => 'Tipos de incidencia creados con éxito!'], Response::HTTP_CREATED);
    }

    public static function crearTipos()
    {
        $tiposIncidencia = [
            ['tipo' => 'Fallo Mecánico', 'prioridad' => 3],
            ['tipo' => 'Fallo Eléctrico', 'prioridad' => 3],
            ['tipo' => 'Mantenimiento Preventivo', 'prioridad' => 1],
            ['tipo' => 'Mantenimiento Correctivo', 'prioridad' => 2],
            ['tipo' => 'Reparación Urgente', 'prioridad' => 3],
            ['tipo' => 'Ajuste de Máquina', 'prioridad' => 1],
            ['tipo' => 'Lubricación', 'prioridad' => 1],
            ['tipo' => 'Reemplazo de Piezas', 'prioridad' => 2],
            ['tipo' => 'Inspección de Seguridad', 'prioridad' => 2],
            ['tipo' => 'Actualización de Software', 'prioridad' => 1],
            ['tipo' => 'Calibración', 'prioridad' => 2],
            ['tipo' => 'Parada de Emergencia', 'prioridad' => 3],
            ['tipo' => 'Problema de Calidad', 'prioridad' => 2],
            ['tipo' => 'Fallo de Sensor', 'prioridad' => 3],
            ['tipo' => 'Desgaste de Herramienta', 'prioridad' => 2],
        ];

        foreach ($tiposIncidencia as $tipo) {
            $tipoIncidencia = new TipoIncidencia();
            $tipoIncidencia->tipo = $tipo['tipo'];
            $tipoIncidencia->prioridad = $tipo['prioridad'];
            $tipoIncidencia->save();
        }
    }

    public function all(){
        try{
            $tiposIncidencia = TipoIncidencia::all();
            return response()->json($tiposIncidencia, Response::HTTP_OK);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al obtener los tipos de incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(TipoIncidencia $tipoIncidencia){
        try{
            return response()->json($tipoIncidencia, Response::HTTP_OK);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al obtener el tipo de incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(TipoIncidencia $tipoIncidencia, Request $request){
        try{
            $tipoIncidencia->update($request->all());
            return response()->json($tipoIncidencia, Response::HTTP_OK);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al actualizar el tipo de incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(TipoIncidencia $tipoIncidencia){
        try{
            $tipoIncidencia->delete();
            return response()->json(['message' => 'Tipo de incidencia eliminado con éxito!'], Response::HTTP_OK);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al eliminar el tipo de incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'tipo' => 'required|string|max:255',
                'prioridad' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $tipoIncidencia = new TipoIncidencia();
            $tipoIncidencia->tipo = $request->get('tipo'); 
            $tipoIncidencia->prioridad = $request->get('prioridad');
            $tipoIncidencia->save();

            return response()->json($tipoIncidencia, Response::HTTP_CREATED);
            
        }
        catch (Exception $e) {
            return response()->json(['error' => 'Error al crear el tipo de incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
