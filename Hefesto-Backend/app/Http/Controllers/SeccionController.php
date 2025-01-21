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
            $seccion->nombre_seccion = $request->get('nombre_seccion');
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
        try {$query = Seccion::query();

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
                'nombre_seccion' => 'required|string|max:255',
                'id_campus' => '|integer',
                'habilitado'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            if($request->get('habilitado')&& $request->get('id_campus')&& $request->get('nombre_seccion')){
                $seccion->update([
                    'nombre_seccion' => $request->get('nombre_seccion'),
                    'id_campus' => $request->get('id_campus'),
                    'habilitado' => $request->get('habilitado')
                ]);
            }
            elseif($request->get('habilitado')&& $request->get('id_campus')){
                $seccion->update([
                    'id_campus' => $request->get('id_campus'),
                    'habilitado' => $request->get('habilitado')
                ]);
            }
            elseif($request->get('habilitado')&& $request->get('nombre_seccion')){
                $seccion->update([
                    'nombre_seccion' => $request->get('nombre_seccion'),
                    'habilitado' => $request->get('habilitado')
                ]);
            }
            else{
                $seccion->update([
                    'nombre_seccion' => $request->get('nombre_seccion')
                ]);
            }
            return response()->json(['message' => 'Seccion actualizada con éxito!','data' => $seccion],Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habido un error al actualizar la seccion'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
