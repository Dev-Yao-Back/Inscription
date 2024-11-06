<?php

namespace App\Filament\Resources\FilliereResource\Pages;

use App\Filament\Resources\FilliereResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFillieres extends ListRecords
{
    protected static string $resource = FilliereResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
