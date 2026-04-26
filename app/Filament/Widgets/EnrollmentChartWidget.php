<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;

class EnrollmentChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Pendaftaran 6 Bulan Terakhir';
    protected static ?int    $sort    = 2;
    protected static ?string $maxHeight = '180px';
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data   = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month    = now()->subMonths($i);
            $labels[] = $month->format('M');
            $data[]   = Enrollment::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Pendaftaran',
                    'data'            => $data,
                    'backgroundColor' => 'rgba(14,165,233,0.8)',
                    'borderRadius'    => 4,
                    'borderSkipped'   => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'ticks' => ['stepSize' => 1],
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
