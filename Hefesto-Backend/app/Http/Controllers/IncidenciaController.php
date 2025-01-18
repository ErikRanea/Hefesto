<?php
use Illuminate\Http\Response;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncidenciaController{

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
                'fecha_apertura' => 'required',
                'descripcion' => 'required',
                'id_maquina' => 'required',
            ]);



            $data = request()->all();
            $incidencia = Incidencia::create($data);
            return response()->json($incidencia, Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al crear la incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}