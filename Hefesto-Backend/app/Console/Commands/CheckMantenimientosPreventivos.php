<?php

namespace App\Console\Commands;

use App\Http\Controllers\IncidenciaController;
use App\Models\Incidencia;
use Illuminate\Console\Command;
use App\Models\MantenimientoPreventivo;
use App\Models\MantenimientoMaquina;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckMantenimientosPreventivos extends Command
{
    protected $signature = 'mantenimientos:check';
    protected $description = 'Verifica los mantenimientos asignados y crea incidencias si es necesario';

    public function handle()
    {
        $hoy = Carbon::now()->toDateString();
        $mantenimientoMaquinas = MantenimientoMaquina::whereDate('fecha_proximo', $hoy)->get();

         $this->info('Mantenimiento Maquinas: ' . ($mantenimientoMaquinas->isNotEmpty() ? "true" : "false"));
        if ($mantenimientoMaquinas->isNotEmpty()) {
             foreach ($mantenimientoMaquinas as $mantenimiento) {
                    $mantenimientoPreventivo = MantenimientoPreventivo::find($mantenimiento->id_mantenimiento);
                    $this->info('El id del mantenimiento preventivo es '.$mantenimiento->id_mantenimiento);

                  if ($mantenimientoPreventivo) {
                        
                        IncidenciaController::crearIncidenciaMantenimiento($mantenimiento);
                        $this->info('Mantenimiento creado para: ' . $mantenimientoPreventivo->nombre . ' - Maquina: ' . $mantenimiento->id_maquina);
                   } else {
                          Log::error('No se encontró el mantenimiento preventivo con id: ' . $mantenimiento->id_mantenimiento);
                         $this->error('No se encontró el mantenimiento preventivo con id: ' . $mantenimiento->id_mantenimiento);
                   }
                }
        }
           $this->info('Proceso de mantenimiento preventivo completado');
    }
}