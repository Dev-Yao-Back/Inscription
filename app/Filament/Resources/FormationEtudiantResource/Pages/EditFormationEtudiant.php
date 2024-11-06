<?php

namespace App\Filament\Resources\FormationEtudiantResource\Pages;

use App\Filament\Resources\FormationEtudiantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormationEtudiant extends EditRecord
{
    protected static string $resource = FormationEtudiantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
