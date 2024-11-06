<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type_etablissement',
        'adresse',
        'telephone',
        'email',
        'site_web',
        'responsable',
        'annee_fondation',
        'nombre_etudiants',
        'nombre_formations',
        'status',
    ];

    public function etudiant()
    {
        return $this->hasMany(Etudiant::class);
    }
}