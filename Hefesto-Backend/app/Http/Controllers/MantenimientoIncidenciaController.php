<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\MantenimientoIncidencia;
use App\Models\MantenimientoPreventivo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MantenimientoIncidenciaController extends Controller
{
    //



    public function store(Request $request){

        try {
            $validator = Validator::make($request->all,[
                'id_incidencia'=>'required|integer',
                'id_mantenimiento' => 'required|integer'
            ],[
                'id_incidencia.required' => 'El campo id_incidencia es obligatorio',
                'id_mantenimiento.required' => 'El campo id_mantenimiento es obligatorio'
            ]);

            if($validator->fails()){
                return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            $mantenimientoIncidencia = new MantenimientoIncidencia();
            $idIncidencia = Incidencia::where('id',$request->get('id_incidencia'))->first();
            if(!$idIncidencia){
                return response()->json(['error' => 'El id incidencia no existe'] , Response::HTTP_BAD_REQUEST);
            }
            $idMantenimiento = MantenimientoPreventivo::where('id',$request->get('id_mantenimienti'))->first();
            if(!$idMantenimiento){
                return response()->json(['error' => 'El id mantenimiento preventivo no existe'], Response::HTTP_BAD_REQUEST);
            }
            $mantenimientoIncidencia -> id_incidencia = $idIncidencia;
            $mantenimientoIncidencia -> id_mantenimiento = $idMantenimiento;
            $mantenimientoIncidencia->save();

            return response()->json(['success' => 'La incidencia del mantenimiento se ha creado correctamente',
                                    $mantenimientoIncidencia], Response::HTTP_ACCEPTED);
                            

        } 
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
