<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class HorariosDisponibles extends Field
{
    protected string $view = 'filament.components.horarios-disponibles';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrated(false);
    }

    public function areaId(int|string|null $areaId): static
    {
        $this->viewData(['areaId' => $areaId]);

        return $this;
    }
}
