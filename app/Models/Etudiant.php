<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etudiant extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'email',
        'telephone',
        'adresse',
        'niveau_etude',
        'annee_academique',
        'filliere_id',
        'ecole_id',
        'photo',
    ];



    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function filliere()
    {
        return $this->belongsTo(Filliere::class);
    }

    public function formationetudiant()
    {
        return $this->hasMany(FormationEtudiant::class);
    }

    public function routeNotificationForMail($notification)
{
    return 'email-universite@exemple.com'; // Adresse e-mail de l'université
}

}