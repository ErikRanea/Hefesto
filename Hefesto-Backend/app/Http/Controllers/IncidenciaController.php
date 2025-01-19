<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Incidencia;
use App\Models\Maquina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use App\Models\TipoIncidencia;
use Exception;

class IncidenciaController extends Controller
{
    public function all(){
        try{
            $incidencias = Incidencia::all();
            return response()->json($incidencias, Response::HTTP_OK);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al obtener los incidencias.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'descripcion' => 'required',
                'id_maquina' => 'required',
                'tipo_incidencia' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $incidencia = new Incidencia();
            $incidencia->fecha_apertura = Date::now();
            $incidencia->id_maquina = $request->get('id_maquina');
            $tipoIncidencia = TipoIncidencia::find($request->get('tipo_incidencia'));
            $incidencia->id_tipo_incidencia = $tipoIncidencia->id;
            $incidencia->descripcion = $request->get('descripcion');
            $incidencia->id_usuario_reporta = auth()->user()->id;
            $incidencia->save();

            return response()->json(['message' => 'Incidencia creada con éxito!', 'data' => $incidencia], Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al crear la incidencia.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id){
        try{
            $incidencia = Incidencia::findOrFail($id);
            return response()->json(['data' => $incidencia], Response::HTTP_ACCEPTED);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al obtener la incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update_estado($id)
    {
        try {

            $incidencia = Incidencia::findOrFail($id);

            $incidencia->estado = !$incidencia->estado;
            $incidencia->save();

            return response()->json(['message' => 'Estado de la incidencia actualizado con éxito!', 'data' => $incidencia], Response::HTTP_OK);
        }
        catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar el estado de la incidencia.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id){
        try {
            $incidencia = Incidencia::findOrFail($id);
            $incidencia->delete();
            return response()->json(['message' => 'Incidencia eliminada con éxito!'], Response::HTTP_OK);
        }
        catch (\Throwable $th) {
            return response()->json(['error' => 'Error al eliminar la incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);    
        }
    }


    public function asignarIncidencia($id){
        try {
            $incidencia = Incidencia::findOrFail($id);
            $incidencia->id_usuario_asignado = auth()->user()->id;
            $incidencia->save();
            //trigger 
            return response()->json(['message' => 'Incidencia asignada con éxito!', 'data' => $incidencia], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error al asignar la incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

}