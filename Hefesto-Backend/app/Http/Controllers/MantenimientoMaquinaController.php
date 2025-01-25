<?php

namespace App\Http\Controllers;

use App\Models\MantenimientoMaquina;
use App\Models\MantenimientoPreventivo;
use App\Models\Maquina;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MantenimientoMaquinaController extends Controller
{
    //

    public function asignarMantenimiento(Request $request){
        try {
            //code...

        

            $validator = Validator::make($request->all(),[
                'id_maquina'=>['required','integer'],
                'id_mantenimiento'=> ['required','integer'],
            ],[
                'id_maquina.required' => 'El campo id_maquina es obligatorio',
                'id_mantenimiento.required' => 'El campo id_mantenimiento es obligatorio'
            ]);

            if($validator->fails()){
                return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            $mantenimientoMaquina = new MantenimientoMaquina();
            $mantenimiento = MantenimientoPreventivo::where('id',$request->get('id_mantenimiento'))->first();
            if(!$mantenimiento){
                return response()->json(['error' => 'El id mantenimiento preventivo no existe'], Response::HTTP_BAD_REQUEST);
            }
            $maquina = Maquina::where('id',$request->get('id_maquina'))->first();
            if(!$maquina){
                return response()->json(['error' => 'El id maquina no existe '],Response::HTTP_BAD_REQUEST);
            }
            $mantenimientoMaquina->id_maquina = $maquina->id;
            $mantenimientoMaquina->id_mantenimiento = $mantenimiento->id;
            $mantenimientoMaquina->fecha_proximo = Carbon::now()->addDays($mantenimiento->periodicidad);
            $mantenimientoMaquina->save();

            return response()->json(['success' => 'Se asigno correctamente el mantenimiento a la maquina','data' => $mantenimientoMaquina],Response::HTTP_ACCEPTED);

        } 
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



}
