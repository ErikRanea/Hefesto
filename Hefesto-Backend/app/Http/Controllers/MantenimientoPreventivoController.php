<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MantenimientoPreventivo;
use Exception;
use Illuminate\Http\Response;


class MantenimientoPreventivoController extends Controller
{
    //
    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string|max:255',
                'periodicidad' => 'required|integer',
                'id_maquina' => 'required|integer',
                'fecha_ultimo_mantenimiento' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $mantenimiento = new MantenimientoPreventivo();
            $mantenimiento->nombre = $request->get('nombre');
            $mantenimiento->descripcion = $request->get('descripcion');
            $mantenimiento->periodicidad = $request->get('periodicidad');
            $mantenimiento->id_maquina = $request->get('id_maquina');
            $mantenimiento->fecha_ultimo_mantenimiento = $request->get('fecha_ultimo_mantenimiento');
            $mantenimiento->habilitado = $request->get('habilitado') == false ? 1 : $request->get('habilitado');
            $mantenimiento->estado = 'pendiente';
            $mantenimiento->habilitado = true;
            $mantenimiento->save();

            return response()->json(['message' => 'Mantenimiento preventivo creado con éxito!', 'data' => $mantenimiento], Response::HTTP_CREATED);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear el mantenimiento preventivo.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function all()
    {
        try {
            $mantenimientos = MantenimientoPreventivo::all();
            return response()->json(['message' => 'Lista de todos los mantenimientos preventivos', 'data' => $mantenimientos], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habido un error al solicitar los mantenimientos preventivos'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        //
        try {
            $mantenimiento = MantenimientoPreventivo::find($id);
            return response()->json(['data' => $mantenimiento], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habido un error al solicitar el mantenimiento preventivo'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(MantenimientoPreventivo $mantenimiento, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string|max:255',
                'periodicidad' => 'required|integer',
                'id_maquina' => 'required|integer',
                'fecha_ultimo_mantenimiento' => 'required|date',
                'habilitado' => 'boolean',
                'estado' => 'string|max:255|required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $mantenimiento->nombre = $request->get('nombre');
            $mantenimiento->descripcion = $request->get('descripcion');
            $mantenimiento->periodicidad = $request->get('periodicidad');
            $mantenimiento->id_maquina = $request->get('id_maquina');
            $mantenimiento->fecha_ultimo_mantenimiento = $request->get('fecha_ultimo_mantenimiento');
            $mantenimiento->habilitado = $request->get('habilitado') == false ? 1 : $request->get('habilitado');
            $mantenimiento->save();

            return response()->json(['message' => 'Mantenimiento preventivo actualizado con éxito!', 'data' => $mantenimiento], Response::HTTP_CREATED);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar el mantenimiento preventivo.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(MantenimientoPreventivo $mantenimiento)
    {
        try {
            $mantenimiento->delete();
            return response()->json(['message' => 'Mantenimiento preventivo eliminado con éxito!'], Response::HTTP_ACCEPTED);
        }
        catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el mantenimiento preventivo.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
