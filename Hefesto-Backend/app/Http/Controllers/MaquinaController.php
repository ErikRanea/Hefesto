<?php

namespace App\Http\Controllers;
use App\Models\Maquina;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaquinaController extends Controller
{
    public function store(Request $request)
    {
        //
        try {
            $validator = Validator::make($request->all(), [
                'nombre_maquina' => 'required|string|max:255',
                'id_seccion' => 'required|integer',
                'numero_interno' => 'required|integer',
                'tipo_maquina' => 'string|max:255',
                'prioridad' => 'required|integer',
                'habilitado' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $maquina = new Maquina();
            $maquina->nombre_maquina = $request->get('nombre_maquina');
            $maquina->id_seccion = $request->get('id_seccion');
            $maquina->numero_interno = $request->get('numero_interno');
            $maquina->tipo_maquina = $request->get('tipo_maquina');
            $maquina->prioridad = $request->get('prioridad');
            $maquina->habilitado = $request->get('habilitado') == false ? 1 : $request->get('habilitado');
            $maquina->save();

            return response()->json(['message' => 'Máquina creada con éxito!', 'data' => $maquina], Response::HTTP_CREATED);

        }
        catch (Exception $e) {
            return response()->json(['error' => 'Error al crear la máquina.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function all(Request $request)
    {
        try {
            $query = Maquina::query();

            // Filtro por sección
            if ($request->has('nombre_seccion') && $request->get('nombre_seccion') != null) {
                $query->where('nombre_seccion', $request->get('nombre_seccion'));
            }

            // Filtro por campus
            if ($request->has('nombre_campus') && $request->get('nombre_campus') != null) {
                $query->where('nombre_campus', $request->get('nombre_campus'));
            }

            // Filtro por nombre de máquina
            if ($request->has('nombre_maquina') && $request->get('nombre_maquina') != null ) {
                $query->where('nombre_maquina', 'like', '%' . $request->get('nombre_maquina') . '%');
            }

            $maquinas = $query->get();
            return response()->json(['message' => 'Lista de todas las máquinas', 'data' => $maquinas], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habido un error al solicitar las máquinas', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function show(Maquina $maquina)
    {
        //
        try {
            return response()->json(['data' => $maquina], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habido un error al solicitar la máquina'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Maquina $maquina, Request $request)
    {
        //
        try {
            $validator = Validator::make($request->all(), [
                'nombre_maquina' => 'string|max:255',
                'id_seccion' => 'integer',
                'numero_interno' => 'integer',
                'tipo_maquina' => 'string|max:255',
                'prioridad' => 'integer',
                'habilitado' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $maquina->nombre_maquina = $request->get('nombre_maquina');
            $maquina->id_seccion = $request->get('id_seccion');
            $maquina->numero_interno = $request->get('numero_interno');
            $maquina->tipo_maquina = $request->get('tipo_maquina');
            $maquina->prioridad = $request->get('prioridad');
            $maquina->habilitado = $request->get('habilitado') == false ? 1 : $request->get('habilitado');
            $maquina->save();

            return response()->json(['message' => 'Máquina actualizada con éxito!', 'data' => $maquina], Response::HTTP_CREATED);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar la máquina.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function delete(Maquina $maquina)
    {
        //
        try {
            $maquina->delete();
            return response()->json(['message' => 'Máquina eliminada con éxito!'], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar la máquina.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    

}

