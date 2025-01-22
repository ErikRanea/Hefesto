<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Incidencia;
use App\Models\Maquina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use App\Models\TipoIncidencia;
use Exception;
use Carbon\Carbon;
use App\Models\Seccion;
use App\Models\Campus;

class IncidenciaController extends Controller
{
    public function all(Request $request){
        try{
            
            $query = Incidencia::query();

            
            if($request->has('id_campus')){
                
                $id_campus = $request->get('id_campus');
                $campusExists = Campus::where('id', $id_campus)->exists();
    
                if ($campusExists) {
                    $query->whereHas('maquina', function($q) use ($request){
                        $q->whereHas('seccion', function($q) use ($request){
                            $q->where('id_campus', $request->get('id_campus'));
                        });
                    });
                } else {
                    return response()->json(["El campus ".$request->get('id_campus').' no existe'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            if($request->has('id_seccion')){
                $id_seccion = $request->get('id_seccion');
                $seccionExists = Seccion::where('id', $id_seccion)->exists();
    
                if ($seccionExists) {
                    $query->whereHas('maquina', function($q) use ($request){
                        $q->where('id_seccion', $request->get('id_seccion'));
                    });
                } else {
                    return response()->json(["La seccion ".$request->get('id_seccion').' no existe'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
    

    
            $incidencias = $query->get();
            return response()->json($incidencias, Response::HTTP_OK);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al obtener los incidencias.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'titulo' => 'required',
                'subtitulo' => 'required',
                'descripcion' => 'required',
                'id_maquina' => 'required',
                'tipo_incidencia' => 'required',
                'id_mantenimiento'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $incidencia = new Incidencia();
            $incidencia->fecha_apertura = Date::now();
            $incidencia->id_maquina = $request->get('id_maquina');
            $tipoIncidencia = TipoIncidencia::find($request->get('tipo_incidencia'));
            $incidencia->id_tipo_incidencia = $tipoIncidencia->id;
            $incidencia->titulo = $request->get('titulo');
            $incidencia->subtitulo = $request->get('subtitulo');
            $request->get('descripcion') != null ? $incidencia->descripcion = $request->get('descripcion'): null;
            $incidencia->id_creador = auth()->user()->id;
            $incidencia->estado = 0;
            if($request->get('id_mantenimiento') != null){
                $incidencia->id_mantenimiento = $request->get('id_mantenimiento');  

            }
            $incidencia->save();

            return response()->json(['message' => 'Incidencia creada con éxito!', 'data' => $incidencia], Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al crear la incidencia.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id){
        try{
            $incidencia = Incidencia::findOrFail($id);
            return response()->json(['data' => $incidencia], Response::HTTP_ACCEPTED);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al obtener la incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function delete($id){
        try {
            $incidencia = Incidencia::findOrFail($id);
            $incidencia->delete();
            return response()->json(['message' => 'Incidencia eliminada con éxito!'], Response::HTTP_OK);
        }
        catch (\Throwable $th) {
            return response()->json(['error' => 'Error al eliminar la incidencia.'], Response::HTTP_INTERNAL_SERVER_ERROR);    
        }
    }    
    

    //Metodos internos

    /*
        Estados de las incidencias
        0-> nuevo
        1-> pendiente
        2-> en curso
        3-> cerrado
        4-> mantenimiento
    */

    /*
        Estos metodos serás static debido a que quiero llamarlos desde otros controladores
        sin tener que instanciarlos

        @author: Erik

    */

    public static function estadoEnCurso(Incidencia $incidencia){
        $incidencia->estado = 2;
        $incidencia->save();
    }
    
    public static function estadoEnEspera(Incidencia $incidencia){
        $incidencia->estado = 1;
        $incidencia->save();
    }

    public static function estadoCerrado(Incidencia $incidencia){
        $incidencia->estado = 3;
        $incidencia->fecha_cerrado = Date::now();
        $incidencia->save();
    }

    public static function estadoMantenimiento(Incidencia $incidencia){
        $incidencia->estado = 4;
        $incidencia->save();
    }

}