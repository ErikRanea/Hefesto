<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Incidencia;
use App\Models\Maquina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\TipoIncidencia;
use Exception;
use Carbon\Carbon;
use App\Models\Seccion;
use App\Models\Campus;

class IncidenciaController extends Controller
{
    public function all(Request $request)
    {
        try {
            $query = Incidencia::query()
                ->where('habilitado', 1);

            // Filtro por campus si se proporciona
            if ($request->has('id_campus')) {
                $id_campus = $request->get('id_campus');
                $campusExists = Campus::where('id', $id_campus)->exists();

                if ($campusExists) {
                    $query->whereHas('maquina', function ($q) use ($request) {
                        $q->whereHas('seccion', function ($q) use ($request) {
                            $q->where('id_campus', $request->get('id_campus'));
                        });
                    });
                } else {
                    return response()->json(["El campus " . $id_campus . ' no existe'], Response::HTTP_NOT_FOUND);
                }
            }

            // Filtro por sección si se proporciona
            if ($request->has('id_seccion')) {
                $id_seccion = $request->get('id_seccion');
                $seccionExists = Seccion::where('id', $id_seccion)->exists();

                if ($seccionExists) {
                    $query->whereHas('maquina', function ($q) use ($request) {
                        $q->where('id_seccion', $request->get('id_seccion'));
                    });
                } else {
                    return response()->json(["La sección " . $id_seccion . ' no existe'], Response::HTTP_NOT_FOUND);
                }
            }

            $incidencias = $query->get();
           
            // Ordenar las incidencias primero por computo_prioridad descendente, y luego por fecha_apertura ascendente (más antiguas primero).
           $incidenciasOrdenadas = $incidencias->sortBy(function ($incidencia) {
                return [-$incidencia->computo_prioridad, $incidencia->fecha_apertura];
            });
            
            return response()->json($incidenciasOrdenadas->values()->all(), Response::HTTP_OK);

        } catch (Exception $e) {
            // Registrar el error para depuración
            Log::error("Error al obtener incidencias: " . $e->getMessage());
            return response()->json(['error' => 'Error al obtener las incidencias. Por favor, inténtalo de nuevo más tarde.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function allConCerradas(Request $request)
    {
        try {
            $query = Incidencia::query()
            ->with(['tipoIncidencia', 'maquina']); 


            // Filtro por campus si se proporciona
            if ($request->has('id_campus')) {
                $id_campus = $request->get('id_campus');
                $campusExists = Campus::where('id', $id_campus)->exists();

                if ($campusExists) {
                    $query->whereHas('maquina', function ($q) use ($request) {
                        $q->whereHas('seccion', function ($q) use ($request) {
                            $q->where('id_campus', $request->get('id_campus'));
                        });
                    });
                } else {
                    return response()->json(["El campus " . $id_campus . ' no existe'], Response::HTTP_NOT_FOUND);
                }
            }

            // Filtro por sección si se proporciona
            if ($request->has('id_seccion')) {
                $id_seccion = $request->get('id_seccion');
                $seccionExists = Seccion::where('id', $id_seccion)->exists();

                if ($seccionExists) {
                    $query->whereHas('maquina', function ($q) use ($request) {
                        $q->where('id_seccion', $request->get('id_seccion'));
                    });
                } else {
                    return response()->json(["La sección " . $id_seccion . ' no existe'], Response::HTTP_NOT_FOUND);
                }
            }

            $incidencias = $query->get();
           
            // Ordenar las incidencias primero por computo_prioridad descendente, y luego por fecha_apertura ascendente (más antiguas primero).
           $incidenciasOrdenadas = $incidencias->sortBy(function ($incidencia) {
                return [-$incidencia->computo_prioridad, $incidencia->fecha_apertura];
            });
            
            return response()->json($incidenciasOrdenadas->values()->all(), Response::HTTP_OK);

        } catch (Exception $e) {
            // Registrar el error para depuración
            Log::error("Error al obtener incidencias: " . $e->getMessage());
            return response()->json(['error' => 'Error al obtener las incidencias. Por favor, inténtalo de nuevo más tarde.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request){
        try{
            
            $validator = Validator::make($request->all(), [
                'titulo' => 'required',
                'subtitulo' => 'required',
                'descripcion',
                'id_maquina' => 'required',
                'tipo_incidencia' => 'required',
                'id_mantenimiento'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }
            $tipoIncidencia = TipoIncidencia::find($request->get('tipo_incidencia'));         
            $maquina = Maquina::find($request->get('id_maquina'));       
            $computoPrioridad = $tipoIncidencia->prioridad + $maquina ->prioridad;
            $prioridad = '';

            /**@author: Erik
             * 
             * En función de el computo de la prioridad de la máquina y del tipo de incidencia, lo que haremos en asignarle una gravedad
             * 
             * 6 5 | 4 3 | 2
             *alta  media  baja   
             */

            switch($computoPrioridad){
                case 6:
                    $prioridad = 'alta';
                    break;
                case 5:
                    $prioridad = 'alta';
                    break;
                case 4:
                    $prioridad = 'media';
                    break;
                case 3:
                    $prioridad = 'media';
                    break;
                case 2:
                    $prioridad = 'baja';
                    break;
            }

       

            $incidencia = new Incidencia();
            $incidencia->fecha_apertura = Date::now();
            $incidencia->id_maquina = $maquina->id;
            $incidencia->id_tipo_incidencia = $tipoIncidencia->id;
            $incidencia->prioridad = $prioridad;
            $incidencia->computo_prioridad = $computoPrioridad;
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

    /**
     * Actualiza la descripción de una incidencia.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDescription(Request $request, $id)
    {
         try {
            $validator = Validator::make($request->all(), [
                'descripcion' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $incidencia = Incidencia::findOrFail($id);
            $incidencia->descripcion = $request->input('descripcion');
            $incidencia->save();

            return response()->json(['message' => 'Descripción de la incidencia actualizada con éxito', 'data' => $incidencia], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar la descripción de la incidencia.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    
    public function allMantenimientos(Request $request){
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
                    return response()->json(["El campus ".$request->get('id_campus').' no existe'], Response::HTTP_BAD_REQUEST);
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
                    return response()->json(["La seccion ".$request->get('id_seccion').' no existe'], Response::HTTP_BAD_REQUEST);
                }
            }
    
            $query->whereNotNull('id_mantenimiento');

    
            $incidencias = $query->get();
            return response()->json($incidencias, Response::HTTP_OK);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Error al obtener los incidencias.','data'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
    
    public static function estadoEnEspera(Incidencia $incidencia)
{
    if($incidencia->estado != 3){
        $incidencia->estado = 1;
        $incidencia->save();
    }
    return;
}

    public static function estadoCerrado(Incidencia $incidencia){
        $incidencia->estado = 3;
        $incidencia->fecha_cierre = Date::now();
        $incidencia->habilitado = 0;
        $incidencia->save();
    }

    public static function estadoMantenimiento(Incidencia $incidencia){
        $incidencia->estado = 4;
        $incidencia->save();
    }

    public static function cargarIncidencias()
    {
        try {
            $incidenciasData = [
                [
                    'fecha_apertura' => Date::now(),
                    'id_maquina' => 1,
                    'id_tipo_incidencia' => 1,
                    'titulo' => 'Fallo en el motor',
                    'subtitulo' => 'Ruido extraño al arrancar',
                    'descripcion' => 'El motor hace un ruido inusual y parece que vibra más de lo normal. Revisar correas y rodamientos.',
                    'id_creador' => 1,
                    'estado' => 0,
                ],
                [
                    'fecha_apertura' => Date::now(),
                    'id_maquina' => 2,
                    'id_tipo_incidencia' => 3,
                    'titulo' => 'Mantenimiento Semanal',
                    'subtitulo' => 'Revisión general de componentes',
                    'descripcion' => 'Realizar la rutina de mantenimiento preventivo semanal. Lubricar, ajustar y limpiar.',
                    'id_creador' => 1,
                    'estado' => 0,
                ],
                 [
                    'fecha_apertura' => Date::now(),
                    'id_maquina' => 1,
                    'id_tipo_incidencia' => 5,
                    'titulo' => 'Parada de Emergencia',
                    'subtitulo' => 'La máquina dejó de funcionar',
                    'descripcion' => 'Se detuvo inesperadamente y no responde a los controles. Revisar sistema eléctrico y de control inmediatamente.',
                    'id_creador' => 1,
                    'estado' => 0,
                ],
                 [
                    'fecha_apertura' => Date::now(),
                    'id_maquina' => 2,
                    'id_tipo_incidencia' => 13,
                    'titulo' => 'Piezas con defectos',
                    'subtitulo' => 'Revisar calidad de la producción',
                    'descripcion' => 'Las piezas producidas presentan rebabas y falta de precisión. Ajustar parámetros o revisar herramientas.',
                    'id_creador' => 1,
                    'estado' => 0,
                 ],
                  [
                    'fecha_apertura' => Date::now(),
                    'id_maquina' => 1,
                    'id_tipo_incidencia' => 6,
                    'titulo' => 'Ajuste de parámetros',
                     'subtitulo' => 'La máquina está funcionando fuera de rango',
                    'descripcion' => 'Ajustar los parámetros de velocidad y temperatura para un funcionamiento óptimo.',
                    'id_creador' => 1,
                    'estado' => 0,
                  ],
                 [
                    'fecha_apertura' => Date::now(),
                    'id_maquina' => 2,
                    'id_tipo_incidencia' => 14,
                    'titulo' => 'Sensor de proximidad no funciona',
                    'subtitulo' => 'La máquina no detecta la pieza',
                    'descripcion' => 'Revisar el sensor de proximidad, cableado y configuración.',
                    'id_creador' => 1,
                    'estado' => 0,
                 ],
                [
                    'fecha_apertura' => Date::now(),
                    'id_maquina' => 1,
                    'id_tipo_incidencia' => 8,
                    'titulo' => 'Cambiar correa',
                    'subtitulo' => 'La correa está desgastada',
                    'descripcion' => 'Reemplazar la correa de transmisión principal. Pedir la pieza necesaria.',
                    'id_creador' => 1,
                    'estado' => 0,
                 ],
                  [
                    'fecha_apertura' => Date::now(),
                    'id_maquina' => 2,
                    'id_tipo_incidencia' => 7,
                    'titulo' => 'Lubricación general',
                    'subtitulo' => 'Engrasar los puntos de fricción',
                    'descripcion' => 'Realizar lubricación en todos los puntos recomendados en el manual del fabricante.',
                    'id_creador' => 1,
                    'estado' => 0,
                   ],
                   [
                     'fecha_apertura' => Date::now(),
                     'id_maquina' => 1,
                     'id_tipo_incidencia' => 10,
                      'titulo' => 'Actualizar software de control',
                       'subtitulo' => 'Nueva versión disponible',
                     'descripcion' => 'Instalar la nueva versión del software de control de la máquina.',
                     'id_creador' => 1,
                    'estado' => 0,
                   ],
                    [
                    'fecha_apertura' => Date::now(),
                    'id_maquina' => 2,
                     'id_tipo_incidencia' => 11,
                    'titulo' => 'Calibrar medidor de presión',
                      'subtitulo' => 'Mediciones incorrectas',
                   'descripcion' => 'Calibrar el medidor de presión para garantizar mediciones precisas.',
                    'id_creador' => 1,
                    'estado' => 0,
                    ],
            ];
            
            foreach ($incidenciasData as $data) {
                $tipoIncidencia = TipoIncidencia::find($data['id_tipo_incidencia']);
                $maquina = Maquina::find($data['id_maquina']);
                
                if (!$tipoIncidencia || !$maquina) {
                    Log::error("No se pudo encontrar la maquina o el tipo de incidencia con los siguientes ids  id_maquina:". $data['id_maquina']."  tipo_incidencia:". $data['id_tipo_incidencia']);
                     return response()->json(['error' => 'Error al crear la incidencia, revisar logs.'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                $computoPrioridad = $tipoIncidencia->prioridad + $maquina->prioridad;

                  $prioridad = '';

                switch($computoPrioridad){
                    case 6:
                        $prioridad = 'alta';
                        break;
                    case 5:
                        $prioridad = 'alta';
                        break;
                    case 4:
                        $prioridad = 'media';
                        break;
                    case 3:
                        $prioridad = 'media';
                        break;
                    case 2:
                        $prioridad = 'baja';
                        break;
                }

                $incidencia = new Incidencia();
                $incidencia->fill($data); // Usar fill para asignar los datos del array
                $incidencia->prioridad = $prioridad;
                $incidencia->computo_prioridad = $computoPrioridad;

               $incidencia->save();

            }
             return response()->json(['message' => 'Incidencias cargadas con éxito!'], Response::HTTP_CREATED);

        } catch (Exception $e) {
             // Registrar el error para depuración
              Log::error("Error al cargar incidencias: " . $e->getMessage());
              return response()->json(['error' => 'Error al cargar las incidencias, por favor intentalo de nuevo más tarde.', 'data' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}