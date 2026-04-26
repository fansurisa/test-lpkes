<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserTypeChartWidget extends ChartWidget
{
    protected static ?string $heading    = 'Komposisi Pengguna';
    protected static ?int    $sort       = 3;
    protected static ?string $maxHeight  = '180px';
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $nakes    = User::where('user_type', 'nakes')->count();
        $nonNakes = User::where('user_type', 'non_nakes')->count();
        $other    = User::whereNull('user_type')->count();

        return [
            'datasets' => [
                [
                    'data'            => [$nakes, $nonNakes, $other],
                    'backgroundColor' => ['#22c55e', '#0ea5e9', '#64748b'],
                    'borderWidth'     => 0,
                    'hoverOffset'     => 4,
                ],
            ],
            'labels' => ['Tenaga Kesehatan', 'Masyarakat Umum', 'Belum Lengkap'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels'   => ['boxWidth' => 10, 'padding' => 10],
                ],
            ],
            'cutout' => '65%',
        ];
    }
}
