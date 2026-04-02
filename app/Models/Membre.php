<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    protected $fillable = [
        'nom',
        'cultes_autorises',
        'lead',
        'choeur_p1',
        'choeur_p2',
        'choeur_p3',
        'piano1',
        'piano2',
        'solo',
        'basse',
        'batterie',
        'score',
    ];

    protected $casts = [
        'cultes_autorises' => 'array',
        'lead'             => 'boolean',
        'choeur_p1'        => 'boolean',
        'choeur_p2'        => 'boolean',
        'choeur_p3'        => 'boolean',
        'piano1'           => 'boolean',
        'piano2'           => 'boolean',
        'solo'             => 'boolean',
        'basse'            => 'boolean',
        'batterie'         => 'boolean',
    ];
}