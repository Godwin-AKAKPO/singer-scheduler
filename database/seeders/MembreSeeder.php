<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membre;

class MembreSeeder extends Seeder
{
    public function run(): void
    {
        Membre::truncate();

        $membres = [

            // ── LEADS C1 uniquement ──────────────────────────────────────────
            // (présents dans Leads C1 mais pas dans Leads C2)
            ['nom' => 'Juste Will',  'cultes' => ['C1','C2'], 'lead_c1' => true,  'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => true,  'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],

            // ── LEADS C1 ET C2 ───────────────────────────────────────────────
            ['nom' => 'Corneille',   'cultes' => ['C1','C2'], 'lead_c1' => true,  'lead_c2' => true,  'sopra' => false, 'alto' => false, 'tenor' => true,  'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Giolitti',    'cultes' => ['C1','C2'], 'lead_c1' => true,  'lead_c2' => true,  'sopra' => false, 'alto' => false, 'tenor' => true,  'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Linuse',      'cultes' => ['C1','C2'], 'lead_c1' => true,  'lead_c2' => true,  'sopra' => false, 'alto' => true,  'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Mme HOUNDJI', 'cultes' => ['C1','C2'], 'lead_c1' => true,  'lead_c2' => true,  'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Jacob',       'cultes' => ['C1','C2'], 'lead_c1' => true,  'lead_c2' => true,  'sopra' => false, 'alto' => false, 'tenor' => true,  'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => true ],
            ['nom' => 'Benjamin',    'cultes' => ['C1','C2'], 'lead_c1' => true,  'lead_c2' => true,  'sopra' => false, 'alto' => false, 'tenor' => true,  'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Priscille',   'cultes' => ['C1','C2'], 'lead_c1' => true,  'lead_c2' => true,  'sopra' => true,  'alto' => true,  'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Erik',        'cultes' => ['C1','C2'], 'lead_c1' => true,  'lead_c2' => true,  'sopra' => false, 'alto' => false, 'tenor' => true,  'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],

            // ── LEADS C2 UNIQUEMENT ──────────────────────────────────────────
            ['nom' => 'Josiane',     'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => true,  'sopra' => true,  'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Reine',       'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => true,  'sopra' => true,  'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Mme EDOU',    'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => true,  'sopra' => true,  'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Serge',       'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => true,  'sopra' => false, 'alto' => false, 'tenor' => true,  'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Houlda',      'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => true,  'sopra' => false, 'alto' => true,  'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],

            // ── CHOEUR UNIQUEMENT (pas leads) ────────────────────────────────
            ['nom' => 'Mme SODEGLA', 'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => true,  'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Mme ADOTE',   'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => true,  'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Esther A.',   'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => true,  'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Femi',        'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => true,  'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Mme SOUNTON', 'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => true,  'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Paloma',      'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => true,  'alto' => true,  'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Etonam',      'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => true,  'alto' => true,  'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Syntiche',    'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => true,  'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Theodorat',   'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => true,  'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Vernon',      'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => true,  'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => false],

            // ── PIANO 1 ──────────────────────────────────────────────────────
            ['nom' => 'Pdt',         'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => true,  'p2' => false, 'solo' => false, 'basse' => true,  'batt' => false],
            ['nom' => 'Paul',        'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => true,  'p2' => true,  'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Asaph',       'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => true,  'p2' => true,  'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Maxime',      'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => true,  'p2' => true,  'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Castro',      'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => true,  'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Junias',      'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => true,  'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Deo Gratias', 'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => true,  'p2' => true,  'solo' => false, 'basse' => false, 'batt' => false],
            ['nom' => 'Gedeon',      'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => true,  'solo' => false, 'basse' => false, 'batt' => false],

            // ── SOLO ─────────────────────────────────────────────────────────
            ['nom' => 'Ghislain',    'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => true,  'basse' => false, 'batt' => false],
            ['nom' => 'Joel',        'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => true,  'basse' => false, 'batt' => false],
            ['nom' => 'Marwane',     'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => true,  'basse' => false, 'batt' => false],

            // ── BASSE ────────────────────────────────────────────────────────
            ['nom' => 'Shalum',      'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => true,  'batt' => false],
            ['nom' => 'Emmanuel',    'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => true,  'batt' => false],
            ['nom' => 'Gaëtan',      'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => true,  'batt' => false],
            ['nom' => 'Bonaventure', 'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => true,  'batt' => false],
            ['nom' => 'Olivier',     'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => true,  'batt' => false],
            ['nom' => 'Merdis',      'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => true,  'batt' => false],

            // ── BATTERIE ─────────────────────────────────────────────────────
            ['nom' => 'Athanase',    'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => true ],
            ['nom' => 'Joseph',      'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => true ],
            ['nom' => 'Kevin',       'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => true ],
            ['nom' => 'Samuel',      'cultes' => ['C1','C2'], 'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => true ],
            ['nom' => 'Jason',       'cultes' => ['C2'],      'lead_c1' => false, 'lead_c2' => false, 'sopra' => false, 'alto' => false, 'tenor' => false, 'p1' => false, 'p2' => false, 'solo' => false, 'basse' => false, 'batt' => true ],
        ];

        foreach ($membres as $data) {
            Membre::create([
                'nom'              => $data['nom'],
                'cultes_autorises' => $data['cultes'],
                'lead_c1'          => $data['lead_c1'],
                'lead_c2'          => $data['lead_c2'],
                'choeur_sopra'     => $data['sopra'],
                'choeur_alto'      => $data['alto'],
                'choeur_tenor'     => $data['tenor'],
                'piano1'           => $data['p1'],
                'piano2'           => $data['p2'],
                'solo'             => $data['solo'],
                'basse'            => $data['basse'],
                'batterie'         => $data['batt'],
                'score'            => 10,
            ]);
        }
    }
}
