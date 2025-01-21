<?php

namespace App\Console\Commands;

use App\Models\Incidencia;
use Illuminate\Console\Command;
use App\Models\MantenimientoPreventivo;

use Carbon\Carbon;

class CheckMantenimientosPreventivos extends Command
{
    protected $signature = 'mantenimientos:check';
    protected $description = 'Verifica los mantenimientos preventivos y crea mantenimientos si es necesario';

    public function handle()
    {
        $mantenimientosPreventivos = MantenimientoPreventivo::where('habilitado', true)
            ->where('estado', '!=', 'completado')
            ->get();


        foreach ($mantenimientosPreventivos as $mantenimientoPreventivo) {

            $fechaLimite = Carbon::parse($mantenimientoPreventivo->fecha_ultimo_mantenimiento)->addDays($mantenimientoPreventivo->periodicidad);
            if ($fechaLimite->isPast()) {
                Incidencia::create([
                    'fecha_apertura' => now(),
                    'descripcion' => 'Mantenimiento preventivo ' . $mantenimientoPreventivo->nombre . ' por periodicidad.'.$mantenimientoPreventivo->descripcion,
                    'id_maquina' => $mantenimientoPreventivo->id_maquina,
                    'id_mantenimiento_preventivo' => $mantenimientoPreventivo->id,
                ]);

               $this->info('Mantenimiento creado para: ' . $mantenimientoPreventivo->nombre . ' - Maquina: ' . $mantenimientoPreventivo->id_maquina);

            }

        }

        $this->info('Proceso de mantenimiento preventivo completado');
    }
}