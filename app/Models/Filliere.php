<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filliere extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'description',
    ];

    public function etudiant()
    {
        return $this->hasMany(Etudiant::class);
    }
}