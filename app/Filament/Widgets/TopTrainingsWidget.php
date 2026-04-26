<?php

namespace App\Filament\Widgets;

use App\Models\Training;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopTrainingsWidget extends BaseWidget
{
    protected static ?string $heading = 'Top 5 Pelatihan Terpopuler';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Training::withCount('enrollments')
                    ->orderByDesc('enrollments_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Pelatihan')->limit(50),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state) => $state === 'event' ? 'primary' : 'warning')
                    ->formatStateUsing(fn ($state) => $state === 'event' ? 'Event' : 'E-Course'),
                Tables\Columns\TextColumn::make('enrollments_count')->label('Peserta')->badge()->color('success'),
            ])
            ->paginated(false);
    }
}
