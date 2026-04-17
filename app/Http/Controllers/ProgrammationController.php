<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\SessionMensuelle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgrammationController extends Controller
{
    public function generer(SessionMensuelle $session)
    {
        $membres   = Membre::all()->toArray();
        $dimanches = $this->getDimanches($session->annee, $session->mois);
        $absences  = $session->absences ?? [];

        // Compteur par rôle — piano1 et piano2 partagent le même compteur "piano"
        $passages = [];
        foreach ($membres as $m) {
            $passages[$m['nom']] = [
                'lead_c1'      => 0,
                'lead_c2'      => 0,
                'choeur_sopra' => 0,
                'choeur_alto'  => 0,
                'choeur_tenor' => 0,
                'piano'        => 0, // compteur commun piano1 + piano2
                'solo'         => 0,
                'basse'        => 0,
                'batterie'     => 0,
            ];
        }

        $programmation = [];

        foreach ($dimanches as $dimanche) {
            $dejaCeDimanche = [];

            foreach (['C1', 'C2'] as $culte) {
                $disponibles = $this->getMembresDisponibles($membres, $absences, $dimanche, $culte);

                // Exclure ceux déjà programmés ce dimanche (C1 ou C2)
                $disponibles = array_values(array_filter(
                    $disponibles,
                    fn($m) => !in_array($m['nom'], $dejaCeDimanche)
                ));

                $dejaCeCulte = [];
                $roleLeadKey = $culte === 'C1' ? 'lead_c1' : 'lead_c2';

                $lead  = $this->selectionner($disponibles, $roleLeadKey, $passages, 1, $dejaCeCulte);
                $sopra = $this->selectionner($disponibles, 'choeur_sopra', $passages, 2, $dejaCeCulte);
                $alto  = $this->selectionner($disponibles, 'choeur_alto',  $passages, 1, $dejaCeCulte);
                $tenor = $this->selectionner($disponibles, 'choeur_tenor', $passages, 1, $dejaCeCulte);

                // Piano — pool commun piano1+piano2, on choisit les 2 avec le moins de passages
                [$piano1, $piano2] = $this->selectionnerPiano($disponibles, $passages, $dejaCeCulte);

                $solo     = $this->selectionner($disponibles, 'solo',     $passages, 1, $dejaCeCulte);
                $basse    = $this->selectionner($disponibles, 'basse',    $passages, 1, $dejaCeCulte);
                $batterie = $this->selectionner($disponibles, 'batterie', $passages, 1, $dejaCeCulte);

                $programmation[] = [
                    'date'    => $dimanche,
                    'culte'   => $culte,
                    'lead'    => $lead,
                    'choeur'  => [
                        'sopra' => $sopra,
                        'alto'  => $alto,
                        'tenor' => $tenor,
                    ],
                    'piano1'   => $piano1,
                    'piano2'   => $piano2,
                    'solo'     => $solo,
                    'basse'    => $basse,
                    'batterie' => $batterie,
                ];

                $dejaCeDimanche = array_merge($dejaCeDimanche, $dejaCeCulte);
            }
        }

        $session->update(['programmation' => $programmation]);

        return redirect()->route('sessions.show', $session)
                         ->with('success', 'Programmation générée avec succès.');
    }

    // Sélectionne piano1 et piano2 depuis le pool commun des pianistes
    // Règle : équité sur le compteur "piano", pas de doublon dans le même culte
    private function selectionnerPiano(array $disponibles, array &$passages, array &$dejaAssignes): array
    {
        // Pool = tous ceux qui font piano1 OU piano2 et pas encore assignés ce culte
        $pool = array_values(array_filter(
            $disponibles,
            fn($m) => ($m['piano1'] || $m['piano2']) && !in_array($m['nom'], $dejaAssignes)
        ));

        if (empty($pool)) return [[], []];

        // Mélange aléatoire puis tri par passages piano croissant, score décroissant
        shuffle($pool);
        usort($pool, function ($a, $b) use ($passages) {
            $diff = $passages[$a['nom']]['piano'] - $passages[$b['nom']]['piano'];
            return $diff !== 0 ? $diff : $b['score'] - $a['score'];
        });

        $piano1Membre = null;
        $piano2Membre = null;

        // Parcourir le pool pour affecter piano1 et piano2
        // — priorité : quelqu'un qui fait piano1 pour le poste piano1
        //              quelqu'un qui fait piano2 pour le poste piano2
        // — fallback : n'importe quel pianiste disponible
        foreach ($pool as $m) {
            if ($piano1Membre === null && $m['piano1'] && !in_array($m['nom'], $dejaAssignes)) {
                $piano1Membre = $m;
                $dejaAssignes[] = $m['nom'];
                $passages[$m['nom']]['piano']++;
            }
        }

        foreach ($pool as $m) {
            if ($piano2Membre === null && $m['piano2'] && !in_array($m['nom'], $dejaAssignes)) {
                $piano2Membre = $m;
                $dejaAssignes[] = $m['nom'];
                $passages[$m['nom']]['piano']++;
            }
        }

        return [
            $piano1Membre ? [$piano1Membre['nom']] : [],
            $piano2Membre ? [$piano2Membre['nom']] : [],
        ];
    }

    public function modifier(Request $request, SessionMensuelle $session)
    {
        $request->validate(['programmation' => 'required|array']);
        $session->update(['programmation' => $request->programmation]);
        return back()->with('success', 'Programmation mise à jour.');
    }

    public function membresDisponibles(Request $request, SessionMensuelle $session)
    {
        $request->validate([
            'dimanche' => 'required|date',
            'culte'    => 'required|in:C1,C2',
            'role'     => 'required|string',
        ]);

        $dimanche = $request->dimanche;
        $culte    = $request->culte;
        $role     = $request->role;

        // Membres déjà programmés ce dimanche (C1 ET C2)
        $programmation = $session->programmation ?? [];
        $dejaProgrammes = [];

        foreach ($programmation as $entry) {
            if ($entry['date'] !== $dimanche) continue;
            $dejaProgrammes = array_merge($dejaProgrammes, $entry['lead'] ?? []);
            $dejaProgrammes = array_merge($dejaProgrammes, $entry['choeur']['sopra'] ?? []);
            $dejaProgrammes = array_merge($dejaProgrammes, $entry['choeur']['alto']  ?? []);
            $dejaProgrammes = array_merge($dejaProgrammes, $entry['choeur']['tenor'] ?? []);
            $dejaProgrammes = array_merge($dejaProgrammes, $entry['piano1']   ?? []);
            $dejaProgrammes = array_merge($dejaProgrammes, $entry['piano2']   ?? []);
            $dejaProgrammes = array_merge($dejaProgrammes, $entry['solo']     ?? []);
            $dejaProgrammes = array_merge($dejaProgrammes, $entry['basse']    ?? []);
            $dejaProgrammes = array_merge($dejaProgrammes, $entry['batterie'] ?? []);
        }

        $dejaProgrammes = array_unique(array_filter($dejaProgrammes));

        // Absences ce dimanche
        $absences = $session->absences ?? [];
        $absentsJour = [];
        foreach ($absences as $absence) {
            if (in_array($dimanche, $absence['dates'] ?? [])) {
                $absentsJour[] = $absence['nom'];
            }
        }

        // Colonne BD correspondant au rôle
        $colonneRole = match($role) {
            'lead'         => $culte === 'C1' ? 'lead_c1' : 'lead_c2',
            'choeur_sopra' => 'choeur_sopra',
            'choeur_alto'  => 'choeur_alto',
            'choeur_tenor' => 'choeur_tenor',
            'piano1'       => 'piano1',
            'piano2'       => 'piano2',
            'solo'         => 'solo',
            'basse'        => 'basse',
            'batterie'     => 'batterie',
            default        => null,
        };

        if (!$colonneRole) return response()->json([]);

        $membres = Membre::where($colonneRole, true)
            ->whereJsonContains('cultes_autorises', $culte)
            ->orderBy('nom')
            ->get(['nom'])
            ->pluck('nom')
            ->filter(fn($nom) => !in_array($nom, $dejaProgrammes) && !in_array($nom, $absentsJour))
            ->values();

        return response()->json($membres);
    }

    public function exportPdf(SessionMensuelle $session)
    {
        $moisNoms = [
            1 => 'Janvier', 2 => 'Février',   3 => 'Mars',      4 => 'Avril',
            5 => 'Mai',     6 => 'Juin',       7 => 'Juillet',   8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre',
        ];

        $dimanches     = $this->getDimanches($session->annee, $session->mois);
        $programmation = $session->programmation ?? [];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.programmation', [
            'session'       => $session,
            'moisNom'       => $moisNoms[$session->mois],
            'dimanches'     => $dimanches,
            'programmation' => $programmation,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("programmation-{$moisNoms[$session->mois]}-{$session->annee}.pdf");
    }

    private function getDimanches(int $annee, int $mois): array
    {
        $dimanches = [];
        $date = \Carbon\Carbon::create($annee, $mois, 1)->startOfMonth();
        while ($date->dayOfWeek !== 0) $date->addDay();
        while ($date->month === $mois) {
            $dimanches[] = $date->toDateString();
            $date->addWeek();
        }
        return $dimanches;
    }

    private function getMembresDisponibles(array $membres, array $absences, string $dimanche, string $culte): array
    {
        return array_values(array_filter($membres, function ($m) use ($absences, $dimanche, $culte) {
            if (!in_array($culte, $m['cultes_autorises'])) return false;
            foreach ($absences as $absence) {
                if ($absence['nom'] === $m['nom'] && in_array($dimanche, $absence['dates'])) return false;
            }
            return true;
        }));
    }

    private function selectionner(array $disponibles, string $role, array &$passages, int $nombre, array &$dejaAssignes): array
    {
        $eligibles = array_filter(
            $disponibles,
            fn($m) => ($m[$role] ?? false) === true && !in_array($m['nom'], $dejaAssignes)
        );

        if (empty($eligibles)) return [];

        $eligibles = array_values($eligibles);
        shuffle($eligibles);

        usort($eligibles, function ($a, $b) use ($passages, $role) {
            // Pour les leads, utiliser le compteur commun lead (c1 ou c2)
            $passA = str_starts_with($role, 'lead') ? ($passages[$a['nom']]['lead_c1'] + $passages[$a['nom']]['lead_c2']) : $passages[$a['nom']][$role];
            $passB = str_starts_with($role, 'lead') ? ($passages[$b['nom']]['lead_c1'] + $passages[$b['nom']]['lead_c2']) : $passages[$b['nom']][$role];
            $diff  = $passA - $passB;
            return $diff !== 0 ? $diff : $b['score'] - $a['score'];
        });

        $selectionnes = array_slice($eligibles, 0, $nombre);

        foreach ($selectionnes as $m) {
            if (isset($passages[$m['nom']][$role])) {
                $passages[$m['nom']][$role]++;
            }
            $dejaAssignes[] = $m['nom'];
        }

        return array_column($selectionnes, 'nom');
    }
}