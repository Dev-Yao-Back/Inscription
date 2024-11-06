<?php

namespace App\Exports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\FromCollection;

class EtudiantsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Etudiant::with('filliere', 'ecole')->get(); // Récupère tous les étudiants avec les relations
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Prénom',
            'Date de Naissance',
            'Email',
            'Téléphone',
            'Adresse',
            'Niveau d\'étude',
            'Année académique',
            'Filière',
            'École',
        ];
    }
}
