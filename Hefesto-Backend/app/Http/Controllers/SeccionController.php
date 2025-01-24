<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Seccion;
use App\Models\Campus;
use Exception;
use Illuminate\Support\Facades\Validator;


class SeccionController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);
         $query = Seccion::query();

            // Filtrar por campus si se proporciona
            if ($request->has('id_campus')) {
                $query->where('id_campus', $request->input('id_campus'));
            }

        $secciones = $query->orderBy('nombre_seccion', 'asc')->paginate($perPage);

        return response()->json($secciones);
    }

    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'nombre_seccion' => 'required|string|max:255',
                'id_campus' => 'required|integer|exists:campuses,id' 
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
    
             $seccion = new Seccion();
            $seccion->nombre_seccion = strip_tags($request->get('nombre_seccion'));
            $seccion->id_campus = $request->get('id_campus');
            $seccion->save();
    
            return response()->json(['message' => 'Seccion creada con éxito!','data' => $seccion], Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al crear la seccion.','data'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   public function all(Request $request)
    {
        try {
             $query = Seccion::query();

            // Filtrar por campus si se proporciona
            if ($request->has('id_campus')) {
                $query->where('id_campus', $request->input('id_campus'));
            }

            $secciones = $query->get();
            return response()->json(['message' => 'Lista de todas las secciones', 'data' => $secciones], Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habido un error al solicitar las secciones'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function show(Seccion $seccion)
    {
        try {
            return response()->json(['data'=> $seccion],Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habidpo un error al solicitar la seccion'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Seccion $seccion,Request $request){
        
       try {
            $validator = Validator::make($request->all(), [
                 'nombre_seccion' => ['sometimes','required', 'string', 'max:255'],
                'id_campus' => ['sometimes', 'required','integer','exists:campuses,id'],
                'habilitado' => ['sometimes', 'boolean'],
            ],[
                 'nombre_seccion.required' => 'El nombre de la seccion es obligatorio.',
                'nombre_seccion.max' => 'El nombre de la seccion no puede tener más de :max caracteres.',
                'id_campus.required' => 'El campo id_campus es obligatorio.',
                'id_campus.integer' => 'El id_campus debe ser un número entero.',
                'habilitado.boolean' => 'El campo habilitado debe ser booleano.',
                'id_campus.exists' => 'El id_campus no es valido'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $validatedData = [];
            if ($request->filled('nombre_seccion')) $validatedData['nombre_seccion'] = strip_tags($request->input('nombre_seccion'));
           if ($request->filled('id_campus')) $validatedData['id_campus'] = $request->input('id_campus');
            if ($request->has('habilitado')) $validatedData['habilitado'] = $request->boolean('habilitado');

            $seccion->update($validatedData);
             return response()->json(['message' => 'Seccion actualizada con éxito!','data' => $seccion],Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habido un error al actualizar la seccion'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
     public function enable(Seccion $seccion)
    {
         try {
               $seccion->update(['habilitado' => true]);
            return response()->json(['message' => 'Seccion habilitada'],Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error'=> 'Error al habilitar la seccion'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function disable(Seccion $seccion)
     {
         try {
               $seccion->update(['habilitado' => false]);
            return response()->json(['message' => 'Seccion deshabilitada'],Response::HTTP_OK);
        } catch (Exception $e) {
             return response()->json(['error'=> 'Error al deshabilitar la seccion'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Seccion $seccion){
        try {
            $seccion->delete();
            return response()->json(['message' => 'Seccion eliminada con éxito!'],Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habido un error al eliminar la seccion'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}