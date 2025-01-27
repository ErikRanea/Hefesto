<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Campus;
use Exception;
use Illuminate\Support\Facades\Validator;


class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      $perPage = $request->input('per_page', 20); // Número de campus por página, por defecto 20
        $campus = Campus::orderBy('nombre_campus', 'asc')->paginate($perPage);
        return response()->json($campus);
    }
    public function store(Request $request){
            try{
                $validator = Validator::make($request->all(), [
                    'nombre_campus' => 'required|string|max:255'
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

                $campus = Campus::create([
                    'nombre_campus' => $request->get('nombre_campus')
                ]);

                
                return response()->json(['message' => 'Campus creado con éxito!','data' => $campus],Response::HTTP_CREATED);
            }
            catch(Exception $e){
                return response()->json(['error' => 'Error al crear el campus.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    } 

     public function all(Request $request)
    {
         try {
            $campus = Campus::all();
           return response()->json(['message' => 'Lista de todos los campus','data'=>$campus],Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habido un error al solicitar los campuses'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function show(Campus $campus)
    {
        try {
            return response()->json(['data'=> $campus],Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ha habidpo un error al solicitar el campus'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Campus $campus,Request $request){
        
        try {
            $validator = Validator::make($request->all(), [
                'nombre_campus' => 'required|string|max:255',
                'habilitado' => ['sometimes','boolean']
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            if($request->get('habilitado') && $request->get('nombre_campus')){
                $campus->update([
                    'nombre_campus' => $request->get('nombre_campus'),
                    'habilitado' => $request->get('habilitado')
                ]);
            }
            elseif($request->get('habilitado')){
                $campus->update([
                    'habilitado' => $request->get('habilitado')
                ]);
            }
            else{
                $campus->update([
                    'nombre_campus' => $request->get('nombre_campus')
                ]);
            }
            return response()->json(['message' => 'Campus actualizado con éxito!', 'data' => $campus], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar el campus.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

      public function enable(Campus $campus)
    {
        try {
              $campus->update(['habilitado' => true]);
            return response()->json(['message' => 'Campus habilitado'],Response::HTTP_OK);
        } catch (Exception $e) {
          return response()->json(['error'=> 'Error al habilitar el campus'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function disable(Campus $campus)
     {
         try {
               $campus->update(['habilitado' => false]);
           return response()->json(['message' => 'Campus deshabilitado'],Response::HTTP_OK);
         } catch (Exception $e) {
              return response()->json(['error'=> 'Error al deshabilitar el campus'],Response::HTTP_INTERNAL_SERVER_ERROR);
         }
    }

    public function delete(Campus $campus)
    {
        try {
            $campus -> delete();
            return response()->json(['message' => 'Campus correctamente eliminado'],Response::HTTP_ACCEPTED);
        }
        catch (Exception $e) {
            return response()->json(['error'=> 'Error al eliminar el campus'],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}