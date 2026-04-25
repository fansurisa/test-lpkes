<?php

namespace App\Filament\Resources\SkpRecordResource\Pages;

use App\Filament\Resources\SkpRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkpRecord extends EditRecord
{
    protected static string $resource = SkpRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
