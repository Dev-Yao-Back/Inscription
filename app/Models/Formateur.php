<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formateur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'email',
        'telephone',
        'adresse',
        'specialite',
        'experience',
        'ecole_id',
    ];

    public function formation()
    {
        return $this->hasMany(Formation::class);
    }
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }
}