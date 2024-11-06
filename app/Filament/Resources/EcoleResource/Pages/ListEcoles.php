<?php

namespace App\Filament\Resources\EcoleResource\Pages;

use App\Filament\Resources\EcoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEcoles extends ListRecords
{
    protected static string $resource = EcoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
