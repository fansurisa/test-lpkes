<?php

namespace App\Filament\Resources\SkpRecordResource\Pages;

use App\Filament\Resources\SkpRecordResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSkpRecord extends CreateRecord
{
    protected static string $resource = SkpRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }
}
