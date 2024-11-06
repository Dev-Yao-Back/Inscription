<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationEtudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'formateur_id',
        'formation_id',
        'prix_a_paye',
        'montant',
        'montant_reste',
        'date',
        'moyen_paiement',
        'status',
        'validation'
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function validerInscription()
{
    if ($this->montant_reste == 0) {
        $this->validation = 'validé';
    } else {
        $this->validation = 'non validé';
    }

    $this->save();
}

}