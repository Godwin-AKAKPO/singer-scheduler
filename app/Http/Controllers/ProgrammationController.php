<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\SessionMensuelle;
use Illuminate\Http\Request;

class ProgrammationController extends Controller
{
    public function generer(SessionMensuelle $session)
    {
        $membres = Membre::all()->toArray();
        $dimanches = $this->getDimanches($session->annee, $session->mois);
        $absences = $session->absences ?? [];

        // Initialiser le compteur de passages pour chaque membre
        $passages = [];
        foreach ($membres as $membre) {
            $passages[$membre['nom']] = 0;
        }

        $programmation = [];

        foreach ($dimanches as $dimanche) {
            foreach (['C1', 'C2'] as $culte) {
                // Membres disponibles ce dimanche pour ce culte
                $disponibles = $this->getMembresDisponibles(
                    $membres, $absences, $dimanche, $culte
                );

                $programmation[] = [
                    'date'     => $dimanche,
                    'culte'    => $culte,
                    'lead'     => $this->selectionner($disponibles, 'lead', $passages, 1),
                    'choeur'   => [
                        'p1' => $this->selectionner($disponibles, 'choeur_p1', $passages, 2),
                        'p2' => $this->selectionner($disponibles, 'choeur_p2', $passages, 1),
                        'p3' => $this->selectionner($disponibles, 'choeur_p3', $passages, 1),
                    ],
                    'piano1'   => $this->selectionner($disponibles, 'piano1', $passages, 1),
                    'piano2'   => $this->selectionner($disponibles, 'piano2', $passages, 1),
                    'solo'     => $this->selectionner($disponibles, 'solo', $passages, 1),
                    'basse'    => $this->selectionner($disponibles, 'basse', $passages, 1),
                    'batterie' => $this->selectionner($disponibles, 'batterie', $passages, 1),
                ];
            }
        }

        $session->update(['programmation' => $programmation]);

        return redirect()->route('sessions.show', $session)
                         ->with('success', 'Programmation générée avec succès.');
    }

    // Retourne tous les dimanches du mois
   private function getDimanches(int $annee, int $mois): array
{
    $dimanches = [];
    $date = \Carbon\Carbon::create($annee, $mois, 1)->startOfMonth();

    // Aller au premier dimanche
    while ($date->dayOfWeek !== \Carbon\Carbon::SUNDAY) {
        $date->addDay();
    }

    while ($date->month === $mois) {
        $dimanches[] = $date->toDateString();
        $date->addWeek();
    }

    return $dimanches;
}

    // Filtre les membres disponibles pour un dimanche et un culte donnés
    private function getMembresDisponibles(
        array $membres,
        array $absences,
        string $dimanche,
        string $culte
    ): array {
        return array_filter($membres, function ($membre) use ($absences, $dimanche, $culte) {
            // Vérifier le niveau d'accès au culte
            if (!in_array($culte, $membre['cultes_autorises'])) {
                return false;
            }

            // Vérifier l'absence
            foreach ($absences as $absence) {
                if ($absence['nom'] === $membre['nom'] &&
                    in_array($dimanche, $absence['dates'])) {
                    return false;
                }
            }

            return true;
        });
    }

    public function exportPdf(SessionMensuelle $session)
{
    $moisNoms = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];

    $dimanches = $this->getDimanches($session->annee, $session->mois);
    $programmation = $session->programmation ?? [];

    $data = [
        'session'       => $session,
        'moisNom'       => $moisNoms[$session->mois],
        'dimanches'     => $dimanches,
        'programmation' => $programmation,
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.programmation', $data)
        ->setPaper('a4', 'landscape');

    return $pdf->download("programmation-{$moisNoms[$session->mois]}-{$session->annee}.pdf");
}
    // Sélectionne N membres pour un rôle donné selon équité + score
    private function selectionner(
        array $disponibles,
        string $role,
        array &$passages,
        int $nombre
    ): array {
        // Filtrer ceux éligibles pour ce rôle
        $eligibles = array_filter($disponibles, fn($m) => $m[$role] === true);

        if (empty($eligibles)) {
            return [];
        }

        // Trier : d'abord passages croissant, ensuite score décroissant
        usort($eligibles, function ($a, $b) use ($passages) {
            $passA = $passages[$a['nom']];
            $passB = $passages[$b['nom']];

            if ($passA !== $passB) {
                return $passA - $passB; // moins de passages en premier
            }

            return $b['score'] - $a['score']; // score plus élevé en premier
        });

        // Prendre les N premiers
        $selectionnes = array_slice(array_values($eligibles), 0, $nombre);

        // Incrémenter le compteur de passages
        foreach ($selectionnes as $membre) {
            $passages[$membre['nom']]++;
        }

        return array_column($selectionnes, 'nom');
    }
}