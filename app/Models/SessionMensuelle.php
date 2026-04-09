<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionMensuelle extends Model
{
    protected $table = 'sessions_mensuelles';

    protected $fillable = [
        'annee',
        'mois',
        'absences',
        'programmation',
    ];

    protected $casts = [
        'absences'      => 'array',
        'programmation' => 'array',
    ];
}