<?php

namespace App\Filament\Resources\FormateurResource\Pages;

use App\Filament\Resources\FormateurResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormateurs extends ListRecords
{
    protected static string $resource = FormateurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
