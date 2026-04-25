<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserTypeChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Rasio Nakes vs Non-Nakes';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $nakes    = User::where('user_type', 'nakes')->count();
        $nonNakes = User::where('user_type', 'non_nakes')->count();
        $other    = User::whereNull('user_type')->count();

        return [
            'datasets' => [
                [
                    'data'            => [$nakes, $nonNakes, $other],
                    'backgroundColor' => ['#22c55e', '#0ea5e9', '#94a3b8'],
                ],
            ],
            'labels' => ['Tenaga Kesehatan', 'Masyarakat Umum', 'Belum Lengkap'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
