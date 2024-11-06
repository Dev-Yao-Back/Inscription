<?php

namespace App\Filament\Resources\FilliereResource\Pages;

use App\Filament\Resources\FilliereResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFilliere extends EditRecord
{
    protected static string $resource = FilliereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
