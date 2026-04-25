<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingResource\Pages;
use App\Models\Training;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TrainingResource extends Resource
{
    protected static ?string $model = Training::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Pelatihan';
    protected static ?string $modelLabel = 'Pelatihan';
    protected static ?string $pluralModelLabel = 'Pelatihan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Dasar')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                            $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(Training::class, 'slug', ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\Select::make('type')
                        ->label('Tipe')
                        ->options(['event' => 'Event', 'ecourse' => 'E-Course'])
                        ->required(),

                    Forms\Components\Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                ])->columns(2),

            Forms\Components\Section::make('Konten')
                ->schema([
                    Forms\Components\RichEditor::make('description')
                        ->label('Deskripsi')
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('objectives')
                        ->label('Tujuan Pembelajaran')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Media & Trainer')
                ->schema([
                    Forms\Components\FileUpload::make('thumbnail')
                        ->label('Thumbnail')
                        ->image()
                        ->directory('trainings/thumbnails')
                        ->maxSize(2048),

                    Forms\Components\TextInput::make('trainer_name')
                        ->label('Nama Narasumber'),

                    Forms\Components\TextInput::make('trainer_title')
                        ->label('Jabatan/Gelar Narasumber'),

                    Forms\Components\FileUpload::make('trainer_avatar')
                        ->label('Foto Narasumber')
                        ->image()
                        ->directory('trainings/trainers')
                        ->maxSize(1024),

                    Forms\Components\Textarea::make('trainer_bio')
                        ->label('Bio Narasumber')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Harga & SKP')
                ->schema([
                    Forms\Components\Toggle::make('is_free')
                        ->label('Gratis')
                        ->live()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('price')
                        ->label('Harga (IDR)')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->hidden(fn (Forms\Get $get) => $get('is_free')),

                    Forms\Components\TextInput::make('skp_value')
                        ->label('Nilai SKP')
                        ->numeric()
                        ->default(0)
                        ->minValue(0),
                ])->columns(2),

            Forms\Components\Section::make('Jadwal & Kapasitas')
                ->schema([
                    Forms\Components\DateTimePicker::make('schedule')
                        ->label('Jadwal')
                        ->native(false),

                    Forms\Components\TextInput::make('duration')
                        ->label('Durasi')
                        ->placeholder('Contoh: 3 Hari, 8 Jam'),

                    Forms\Components\TextInput::make('max_participants')
                        ->label('Maks. Peserta')
                        ->numeric()
                        ->placeholder('Kosongkan jika tidak terbatas'),
                ])->columns(3),

            Forms\Components\Section::make('Pelataran Kemenkes')
                ->schema([
                    Forms\Components\TextInput::make('pelataran_link')
                        ->label('Link Pelataran Kemenkes')
                        ->url()
                        ->placeholder('https://plataran.kemkes.go.id/...')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Publikasi')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Dipublikasikan')
                        ->helperText('Hanya pelatihan yang dipublikasikan yang tampil di katalog.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('')
                    ->circular(false)
                    ->width(60)
                    ->height(40),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state) => $state === 'event' ? 'primary' : 'warning')
                    ->formatStateUsing(fn ($state) => $state === 'event' ? 'Event' : 'E-Course'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state, Training $record) =>
                        $record->is_free ? 'GRATIS' : 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('skp_value')
                    ->label('SKP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Peserta')
                    ->counts('enrollments')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options(['event' => 'Event', 'ecourse' => 'E-Course']),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Publikasi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTrainings::route('/'),
            'create' => Pages\CreateTraining::route('/create'),
            'edit'   => Pages\EditTraining::route('/{record}/edit'),
        ];
    }
}
