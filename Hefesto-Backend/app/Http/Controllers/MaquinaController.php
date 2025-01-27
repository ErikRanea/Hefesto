<?php

namespace App\Http\Controllers;
use App\Models\Maquina;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaquinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $perPage = $request->input('per_page', 20);

        $query = Maquina::query();

            // Filtrar por sección si se proporciona
            if ($request->has('id_seccion')) {
                $query->where('id_seccion', $request->get('id_seccion'));
            }

            // Filtrar por campus si se proporciona
            if ($request->has('id_campus')) {
                $query->whereHas('seccion', function ($q) use ($request) {
                    $q->where('id_campus', $request->get('id_campus'));
                });
            }

         $maquinas = $query->orderBy('nombre_maquina', 'asc')->paginate($perPage);
        return response()->json($maquinas);
    }
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
            $maquina->nombre_maquina = strip_tags($request->get('nombre_maquina'));
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


            // Filtrar por sección si se proporciona
            if ($request->has('id_seccion')) {
                $query->where('id_seccion', $request->get('id_seccion'));
            }

            // Filtrar por campus si se proporciona
            if ($request->has('id_campus')) {
                $query->whereHas('seccion', function ($q) use ($request) {
                    $q->where('id_campus', $request->get('id_campus'));
                });
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
                'nombre_maquina' => ['sometimes', 'string', 'max:255'],
                'id_seccion' => ['sometimes','integer'],
                'numero_interno' => ['sometimes','integer'],
                'tipo_maquina' => ['sometimes', 'string', 'max:255'],
                'prioridad' => ['sometimes', 'integer'],
                'habilitado' => ['sometimes', 'boolean']
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validatedData = [];
        if ($request->filled('nombre_maquina')) $validatedData['nombre_maquina'] = strip_tags($request->input('nombre_maquina'));
        if ($request->filled('id_seccion')) $validatedData['id_seccion'] = $request->input('id_seccion');
        if ($request->filled('numero_interno')) $validatedData['numero_interno'] = $request->input('numero_interno');
         if ($request->filled('tipo_maquina')) $validatedData['tipo_maquina'] = $request->input('tipo_maquina');
        if ($request->filled('prioridad')) $validatedData['prioridad'] = $request->input('prioridad');
        if ($request->has('habilitado')) $validatedData['habilitado'] = $request->boolean('habilitado');

            $maquina->update($validatedData);

            return response()->json(['message' => 'Máquina actualizada con éxito!', 'data' => $maquina], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar la máquina.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

  public function enable(Maquina $maquina)
    {
        try {
              $maquina->update(['habilitado' => true]);
            return response()->json(['message' => 'Maquina habilitada'],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error'=> 'Error al habilitar la maquina'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function disable(Maquina $maquina)
     {
         try {
               $maquina->update(['habilitado' => false]);
            return response()->json(['message' => 'Maquina deshabilitada'],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error'=> 'Error al deshabilitar la maquina'],Response::HTTP_INTERNAL_SERVER_ERROR);
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