<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Pribadi')
                ->schema([
                    Forms\Components\TextInput::make('name')->label('Nama')->required(),
                    Forms\Components\TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('phone')->label('No. HP'),
                    Forms\Components\Select::make('user_type')
                        ->label('Tipe')
                        ->options(['nakes' => 'Tenaga Kesehatan', 'non_nakes' => 'Masyarakat Umum']),
                    Forms\Components\TextInput::make('str_number')->label('Nomor STR'),
                    Forms\Components\Select::make('profession')
                        ->label('Profesi')
                        ->options([
                            'dokter' => 'Dokter', 'perawat' => 'Perawat', 'bidan' => 'Bidan',
                            'apoteker' => 'Apoteker', 'analis' => 'Analis Kesehatan',
                            'radiografer' => 'Radiografer', 'fisioterapis' => 'Fisioterapis',
                            'gizi' => 'Ahli Gizi', 'rekam_medis' => 'Rekam Medis', 'lainnya' => 'Lainnya',
                        ]),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('')
                    ->circular()
                    ->getStateUsing(fn (User $record) => $record->avatar_url)
                    ->width(36)->height(36),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('user_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (?string $state) => $state === 'nakes' ? 'success' : 'primary')
                    ->formatStateUsing(fn ($state) => $state === 'nakes' ? 'Nakes' : 'Non-Nakes'),
                Tables\Columns\TextColumn::make('profession')
                    ->label('Profesi')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'dokter' => 'Dokter', 'perawat' => 'Perawat', 'bidan' => 'Bidan',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Pelatihan')
                    ->counts('enrollments')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_type')
                    ->label('Tipe')
                    ->options(['nakes' => 'Tenaga Kesehatan', 'non_nakes' => 'Masyarakat Umum']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit'  => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
