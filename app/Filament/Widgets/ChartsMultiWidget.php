<?php

namespace App\Filament\Widgets;

use Filament\Widgets\WidgetGroup;
use VodafoneZiggoNL\MultiWidget\MultiWidget;


class ChartsMultiWidget extends MultiWidget
{
    public array $widgets = [
        // \App\Filament\Widgets\Charts\FacturaRevenueChartWidget::class,
        // \App\Filament\Widgets\Charts\ConsumoTrendChartWidget::class,
        // \App\Filament\Widgets\Charts\NominaExpenseChartWidget::class,
        // \App\Filament\Widgets\Charts\PagoMethodChartWidget::class,
    ];

     public function shouldPersistMultiWidgetTabsInSession(): bool
    {
        return true;
    }

    
}