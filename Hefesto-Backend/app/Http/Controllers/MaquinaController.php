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

    public function all()
    {
        //
    }
    
    public function show(Maquina $maquina)
    {
        //
    }

    public function update(Maquina $maquina, Request $request)
    {
        //
    }

    public function delete(Maquina $maquina)
    {
        //
    }

}
