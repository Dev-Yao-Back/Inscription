<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'duree',
        'cout',
        'formateur_id',
        'annee_academique',
    ];

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }

    public function formationetudiant()
    {
        return $this->hasMany(FormationEtudiant::class);
    }
}