<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use App\Models\Training;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;

class ActiveUpcomingTrainingsWidget extends Widget
{
    protected static string $view = 'filament.widgets.training-list-widget';
    protected static ?int   $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public string $activeTab    = 'all';
    public int    $selectedYear;

    public function mount(): void
    {
        $this->selectedYear = now()->year;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function setYear(int $year): void
    {
        $this->selectedYear = $year;
    }

    public function getYearOptions(): array
    {
        $years = [];
        for ($y = now()->year; $y >= now()->year - 4; $y--) {
            $years[$y] = $y;
        }
        return $years;
    }

    public function getTabCounts(): array
    {
        $year = $this->selectedYear;

        $all = Training::published()->count();

        $running = Training::published()
            ->whereHas('enrollments', fn (Builder $q) => $q->where('status', 'active')
                ->whereYear('enrolled_at', $year))
            ->count();

        $completed = Training::published()
            ->whereHas('enrollments', fn (Builder $q) => $q->where('status', 'completed')
                ->whereYear('enrolled_at', $year))
            ->count();

        $upcoming = Training::published()
            ->whereNotNull('schedule')
            ->where('schedule', '>=', now())
            ->count();

        return compact('all', 'running', 'completed', 'upcoming');
    }

    public function getTrainings(): \Illuminate\Support\Collection
    {
        $year = $this->selectedYear;

        $query = Training::published()
            ->with('category')
            ->withCount([
                'enrollments as total_enrollments' => fn (Builder $q) => $q->whereYear('enrolled_at', $year),
                'enrollments as active_count'      => fn (Builder $q) => $q->where('status', 'active')->whereYear('enrolled_at', $year),
                'enrollments as completed_count'   => fn (Builder $q) => $q->where('status', 'completed')->whereYear('enrolled_at', $year),
            ]);

        match ($this->activeTab) {
            'running'   => $query->whereHas('enrollments', fn (Builder $q) => $q->where('status', 'active')->whereYear('enrolled_at', $year)),
            'completed' => $query->whereHas('enrollments', fn (Builder $q) => $q->where('status', 'completed')->whereYear('enrolled_at', $year)),
            'upcoming'  => $query->whereNotNull('schedule')->where('schedule', '>=', now()),
            default     => $query, // Semua = semua published training
        };

        return $query->orderByDesc('total_enrollments')->get();
    }
}
