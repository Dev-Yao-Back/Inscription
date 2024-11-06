<?php

namespace App\Filament\Resources\FormationEtudiantResource\Pages;

use App\Filament\Resources\FormationEtudiantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormationEtudiants extends ListRecords
{
    protected static string $resource = FormationEtudiantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
