<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;

class EnrollmentChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Pendaftaran 12 Bulan Terakhir';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data   = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $month    = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[]   = Enrollment::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Pendaftaran',
                    'data'            => $data,
                    'borderColor'     => '#0ea5e9',
                    'backgroundColor' => 'rgba(14,165,233,0.1)',
                    'fill'            => true,
                    'tension'         => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
