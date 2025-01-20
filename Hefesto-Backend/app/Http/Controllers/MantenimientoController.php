<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class MantenimientoController extends Controller
{
    //

    public function store(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'descripcion' => 'required',
                'id_maquina' => 'required',
            ]);
            
            if($validator->fails())
            {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }   

            $mantenimiento = new Mantenimiento();
            $mantenimiento->descripcion = $request->get('descripcion');
            $mantenimiento->id_maquina = $request->get('id_maquina');
            $mantenimiento->fecha_apertura = Date::now();
            $mantenimiento->id_usuario_reporta = auth()->user()->id;
            $mantenimiento->save();

            return response()->json(['message'=>'Mantenimiento creado con éxito']);

        } 
        catch (Exception $e) {
            return response()->json(['error' => 'Error al crear el mantenimiento.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id){
        try {
            $mantenimiento = Mantenimiento::findOrFail($id);
            return response()->json(['data' => $mantenimiento], Response::HTTP_ACCEPTED);
        } 
        catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener el mantenimiento.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function all(){
        try {
            $mantenimientos = Mantenimiento::all();
            return response()->json($mantenimientos, Response::HTTP_OK);
        } 
        catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los mantenimientos.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id){
        try {
            $mantenimiento = Mantenimiento::findOrFail($id);
            $mantenimiento->delete();
            return response()->json(['message' => 'Mantenimiento eliminado con éxito!'], Response::HTTP_OK);
        } 
        catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el mantenimiento.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


       //Metodos internos

    /*
        Estados de las incidencias
        0-> nuevo
        1-> pendiente
        2-> en curso
        3-> cerrado
    */

    /*
        Estos metodos serás static debido a que quiero llamarlos desde otros controladores
        sin tener que instanciarlos

        @author: Erik

    */

    public static function estadoEnCurso(Mantenimiento $mantenimiento){
        $mantenimiento->estado = 2;
        $mantenimiento->save();
    }
    
    public static function estadoEnEspera(Mantenimiento $mantenimiento){
        $mantenimiento->estado = 1;
        $mantenimiento->save();
    }

    public static function estadoCerrado(Mantenimiento $mantenimiento){
        $mantenimiento->estado = 3;
        $mantenimiento->fecha_cierre = Date::now();
        $mantenimiento->save();
    }


}
