<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $thisMonth    = now()->startOfMonth();
        $lastMonth    = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();
        $thisYear     = now()->startOfYear();

        $revenueThisMonth = Order::where('status', 'paid')
            ->where('paid_at', '>=', $thisMonth)
            ->sum('amount');

        $revenueLastMonth = Order::where('status', 'paid')
            ->whereBetween('paid_at', [$lastMonth, $lastMonthEnd])
            ->sum('amount');

        $revenueThisYear = Order::where('status', 'paid')
            ->where('paid_at', '>=', $thisYear)
            ->sum('amount');

        $enrollmentsThisMonth = Enrollment::where('created_at', '>=', $thisMonth)->count();
        $enrollmentsThisYear  = Enrollment::where('created_at', '>=', $thisYear)->count();
        $newUsersThisMonth    = User::where('created_at', '>=', $thisMonth)->count();

        $revenueTrend = $revenueLastMonth > 0
            ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth * 100)
            : 0;

        // Sparkline: pendaftaran 6 bulan
        $enrollmentSparkline = collect(range(5, 0))->map(fn ($i) => Enrollment::whereYear('created_at', now()->subMonths($i)->year)
            ->whereMonth('created_at', now()->subMonths($i)->month)
            ->count()
        )->toArray();

        // Sparkline: revenue 6 bulan
        $revenueSparkline = collect(range(5, 0))->map(fn ($i) => (float) Order::where('status', 'paid')
            ->whereYear('paid_at', now()->subMonths($i)->year)
            ->whereMonth('paid_at', now()->subMonths($i)->month)
            ->sum('amount')
        )->toArray();

        // Sparkline: user baru 6 bulan
        $userSparkline = collect(range(5, 0))->map(fn ($i) => User::whereYear('created_at', now()->subMonths($i)->year)
            ->whereMonth('created_at', now()->subMonths($i)->month)
            ->count()
        )->toArray();

        return [
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'))
                ->description(sprintf('%s%.1f%% dari bulan lalu', $revenueTrend >= 0 ? '↑ ' : '↓ ', abs($revenueTrend)))
                ->descriptionColor($revenueTrend >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->chart($revenueSparkline),

            Stat::make('Pendapatan Tahun Ini', 'Rp ' . number_format($revenueThisYear, 0, ',', '.'))
                ->description('Total tahun ' . now()->year)
                ->icon('heroicon-o-chart-bar')
                ->color('primary'),

            Stat::make('Pendaftaran Bulan Ini', number_format($enrollmentsThisMonth))
                ->description(number_format($enrollmentsThisYear) . ' total tahun ini')
                ->icon('heroicon-o-user-group')
                ->color('warning')
                ->chart($enrollmentSparkline),

            Stat::make('Pengguna Baru Bulan Ini', number_format($newUsersThisMonth))
                ->description('Total: ' . number_format(User::count()) . ' pengguna')
                ->icon('heroicon-o-user-plus')
                ->color('info')
                ->chart($userSparkline),
        ];
    }
}
