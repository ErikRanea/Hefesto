<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class MaquinaController extends Controller
{
    public function index()
    {
        $maquinas = Maquina::orderBy('nombre_maquina', 'asc')->get();
        return response()->json($maquinas);
    }

    public function show(string $id)
    {
        $maquina = Maquina::find($id);

        if (!$maquina) {
            return response()->json(['error' => 'Máquina no encontrada'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($maquina);
    }

    public function update(Request $request, string $id)
    {
        $maquina = Maquina::find($id);

        if (!$maquina) {
            return response()->json(['error' => 'Máquina no encontrada'], Response::HTTP_NOT_FOUND);
        }

         $validator = Validator::make($request->all(), [
            'prioridad' => ['sometimes', 'required', 'integer'],
            'habilitado' => ['sometimes', 'boolean'],
        ], [
            'prioridad.required' => 'El campo prioridad es obligatorio.',
            'prioridad.integer' => 'La prioridad debe ser un valor entero.',
            'habilitado.boolean' => 'El campo habilitado debe ser booleano.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }


        $validatedData = [];
        if ($request->filled('prioridad')) $validatedData['prioridad'] = $request->input('prioridad');
        if ($request->has('habilitado')) $validatedData['habilitado'] = $request->boolean('habilitado');

        $maquina->update($validatedData);

        return response()->json($maquina);
    }
}