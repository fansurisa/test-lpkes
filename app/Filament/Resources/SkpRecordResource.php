<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkpRecordResource\Pages;
use App\Models\SkpRecord;
use App\Models\Training;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SkpRecordResource extends Resource
{
    protected static ?string $model = SkpRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Rekap SKP';
    protected static ?string $modelLabel = 'Rekap SKP';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Peserta')
                ->options(User::where('user_type', 'nakes')->pluck('name', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Select::make('training_id')
                ->label('Pelatihan')
                ->options(Training::where('skp_value', '>', 0)->pluck('title', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('skp_earned')
                ->label('SKP Diperoleh')
                ->numeric()
                ->required()
                ->minValue(1),

            Forms\Components\DatePicker::make('completed_at')
                ->label('Tanggal Selesai')
                ->required()
                ->native(false),

            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->rows(3)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peserta')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.str_number')
                    ->label('Nomor STR'),

                Tables\Columns\TextColumn::make('training.title')
                    ->label('Pelatihan')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('skp_earned')
                    ->label('SKP')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Tgl. Selesai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Peserta'),
                Tables\Filters\SelectFilter::make('training')
                    ->relationship('training', 'title')
                    ->label('Pelatihan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('completed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSkpRecords::route('/'),
            'create' => Pages\CreateSkpRecord::route('/create'),
            'edit'   => Pages\EditSkpRecord::route('/{record}/edit'),
        ];
    }
}
