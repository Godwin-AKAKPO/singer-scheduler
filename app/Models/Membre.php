<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    protected $table = 'membres';

    protected $fillable = [
        'nom',
        'cultes_autorises',
        'lead_c1',
        'lead_c2',
        'choeur_sopra',
        'choeur_alto',
        'choeur_tenor',
        'piano1',
        'piano2',
        'solo',
        'basse',
        'batterie',
        'score',
    ];

    protected $casts = [
        'cultes_autorises' => 'array',
        'lead_c1'          => 'boolean',
        'lead_c2'          => 'boolean',
        'choeur_sopra'     => 'boolean',
        'choeur_alto'      => 'boolean',
        'choeur_tenor'     => 'boolean',
        'piano1'           => 'boolean',
        'piano2'           => 'boolean',
        'solo'             => 'boolean',
        'basse'            => 'boolean',
        'batterie'         => 'boolean',
    ];
}