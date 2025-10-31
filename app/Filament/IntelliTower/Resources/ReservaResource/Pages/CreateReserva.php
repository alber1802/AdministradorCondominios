<?php

namespace App\Filament\IntelliTower\Resources\ReservaResource\Pages;

use App\Filament\IntelliTower\Resources\ReservaResource;
use App\Models\AreaComun;
use App\Models\HorarioDisponible;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Factura;
use App\Models\Departamento;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReserva extends CreateRecord
{
    protected static string $resource = ReservaResource::class;

     
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Validar que el área común esté disponible
        $areaComun = AreaComun::find($data['area_comun_id']);
        
        if (!$areaComun) {
            Notification::make()
                ->title('Error de Validación')
                ->body('El área común seleccionada no existe.')
                ->danger()
                ->send();
            $this->halt();
        }

        if ($areaComun->estado !== 'Disponible') {
            Notification::make()
                ->title('Área No Disponible')
                ->body("El área '{$areaComun->nombre}' no está disponible para reservas en este momento.")
                ->warning()
                ->send();
            $this->halt();
        }

        // Validar fechas
        $fechaInicio = Carbon::parse( $data["fecha_hora_inicio"]);

        $fechaFin = Carbon::parse($data["fecha_hora_fin"]);
        $ahora = Carbon::now();

        // Validar que la fecha de inicio sea futura
        if ($fechaInicio->lte($ahora)) {
            Notification::make()
                ->title('Fecha Inválida')
                ->body('La fecha de inicio debe ser posterior a la fecha y hora actual.')
                ->warning()
                ->send();
            $this->halt();
        }

        // Validar que la fecha de fin sea posterior a la de inicio
        if ($fechaFin->lte($fechaInicio)) {
            Notification::make()
                ->title('Rango de Fechas Inválido')
                ->body('La fecha de fin debe ser posterior a la fecha de inicio.')
                ->warning()
                ->send();
            $this->halt();
        }

        // Validar duración mínima (15 minutos) y máxima (24 horas)
        $duracionHoras = $fechaInicio->diffInMinutes($fechaFin) / 60;
        
        if ($duracionHoras < 0.25) {
            Notification::make()
                ->title('Duración Insuficiente')
                ->body('La reserva debe tener una duración mínima de 15 minutos.')
                ->warning()
                ->send();
            $this->halt();
        }

        if ($duracionHoras > 24) {
            Notification::make()
                ->title('Duración Excesiva')
                ->body('La reserva no puede exceder las 24 horas. Para reservas más largas, cree múltiples reservas.')
                ->warning()
                ->send();
            $this->halt();
        }

        // Validar horario disponible del área común
        $this->validarHorarioDisponible($areaComun, $fechaInicio, $fechaFin);

        // Validar conflictos con otras reservas
        $this->validarConflictosReservas($data['area_comun_id'], $fechaInicio, $fechaFin);
        
        //dd($data);
        $departamento = Departamento::where('user_id',$data['residente_id'])->first();
        
        $recipient = User::find($data['residente_id']);

        if($data['estado_reserva'] == 'pendiente')
        {
            $factura = new Factura();
            $factura->user_id = $data['residente_id'];
            $factura->departamento_id = $departamento->id;
            $factura->tipo = 'reserva de area comun';
            $factura->monto = $data['costo_total_calculado'];
            $factura->estado = 'pendiente';
            $factura->fecha_emision = Carbon::now();
            $factura->fecha_vencimiento = Carbon::parse($data['fecha_hora_fin'])->toDateString();
            $factura->descripcion = 'Factura por reserva de área común: ' . $areaComun->nombre;
            $factura->save();

            // Mostrar notificación de éxito
            Notification::make()
            ->title('Reserva y Factura Creadas')
            ->body('Su reserva ha sido creada exitosamente y se ha generado la factura correspondiente. Tiene hasta el ' . Carbon::parse($data['fecha_hora_fin'])->toDateString() . ' para realizar el pago.')
            ->success()
            ->actions([
                \Filament\Notifications\Actions\Action::make('ver_factura')
                    ->label('Ver Factura')
                    ->url(route('filament.admin.resources.facturas.edit', $factura->id))
                    ->button()
            ])
            ->sendToDatabase($recipient);
        }

        return $data;
    }

    protected function validarHorarioDisponible(AreaComun $areaComun, Carbon $fechaInicio, Carbon $fechaFin): void
    {
        $diaSemana = $fechaInicio->dayOfWeek;
        
        $horarioDisponible = HorarioDisponible::where('area_comun_id', $areaComun->id)
            ->where('dia_semana', $diaSemana)
            ->first();

        if (!$horarioDisponible) {
            Notification::make()
                ->title('Horario No Disponible')
                ->body("El área '{$areaComun->nombre}' no está disponible los días " . $this->getNombreDia($diaSemana) . ".")
                ->warning()
                ->send();
            $this->halt();
        }

        $horaInicio = $fechaInicio->format('H:i:s');
        $horaFin = $fechaFin->format('H:i:s');
        
        $horaApertura = $horarioDisponible->hora_apertura;
        $horaCierre = $horarioDisponible->hora_cierre;

        if ($horaInicio < $horaApertura || $horaInicio >= $horaCierre) {
            Notification::make()
                ->title('Fuera de Horario')
                ->body("La hora de inicio ({$fechaInicio->format('H:i')}) está fuera del horario disponible ({$horaApertura} - {$horaCierre}).")
                ->warning()
                ->send();
            $this->halt();
        }

        if ($horaFin > $horaCierre || $horaFin <= $horaApertura) {
            Notification::make()
                ->title('Fuera de Horario')
                ->body("La hora de fin ({$fechaFin->format('H:i')}) está fuera del horario disponible ({$horaApertura} - {$horaCierre}).")
                ->warning()
                ->send();
            $this->halt();
        }

        if ($fechaInicio->isSameDay($fechaFin) && $horaFin < $horaInicio) {
            Notification::make()
                ->title('Horario Inválido')
                ->body('La reserva no puede cruzar la medianoche. Por favor, cree reservas separadas.')
                ->warning()
                ->send();
            $this->halt();
        }
    }

    protected function validarConflictosReservas(int $areaId, Carbon $fechaInicio, Carbon $fechaFin): void
    {
        $reservasConflicto = Reserva::where('area_comun_id', $areaId)
            ->whereIn('estado_reserva', ['pendiente', 'confirmada'])
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_hora_inicio', [$fechaInicio, $fechaFin])
                    ->orWhereBetween('fecha_hora_fin', [$fechaInicio, $fechaFin])
                    ->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                        $q->where('fecha_hora_inicio', '<=', $fechaInicio)
                          ->where('fecha_hora_fin', '>=', $fechaFin);
                    });
            })
            ->get();

        if ($reservasConflicto->isNotEmpty()) {
            $conflictos = $reservasConflicto->map(function ($reserva) {
                return "• {$reserva->fecha_hora_inicio->format('d/m/Y H:i')} - {$reserva->fecha_hora_fin->format('H:i')}";
            })->join("\n");

            Notification::make()
                ->title('Horario Ocupado')
                ->body("Ya existen reservas en el horario seleccionado:\n\n{$conflictos}")
                ->danger()
                ->persistent()
                ->send();
            $this->halt();
        }
    }

    protected function getNombreDia(int $dia): string
    {
        $dias = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
        ];

        return $dias[$dia] ?? 'Desconocido';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Reserva creada exitosamente';
    }
}
