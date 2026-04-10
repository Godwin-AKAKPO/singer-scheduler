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

        // Compteur par rôle et par membre
        $passages = [];
        foreach ($membres as $membre) {
            $passages[$membre['nom']] = [
                'lead'      => 0,
                'choeur_p1' => 0,
                'choeur_p2' => 0,
                'choeur_p3' => 0,
                'piano1'    => 0,
                'piano2'    => 0,
                'solo'      => 0,
                'basse'     => 0,
                'batterie'  => 0,
            ];
        }

        $programmation = [];

        foreach ($dimanches as $dimanche) {
            // Membres déjà assignés ce dimanche (toutes les deux cultes confondus)
            $dejaProgrammesCeDimanche = [];

            foreach (['C1', 'C2'] as $culte) {
                $disponibles = $this->getMembresDisponibles(
                    $membres, $absences, $dimanche, $culte
                );

                // Exclure ceux déjà programmés ce dimanche (dans l'autre culte)
                $disponibles = array_values(array_filter(
                    $disponibles,
                    fn($m) => !in_array($m['nom'], $dejaProgrammesCeDimanche)
                ));

                // Membres déjà assignés dans CE culte (pour éviter double rôle)
                $dejaAssignesCeCulte = [];

                $lead = $this->selectionner(
                    $disponibles, 'lead', $passages, 1, $dejaAssignesCeCulte
                );

                $choeurP1 = $this->selectionner(
                    $disponibles, 'choeur_p1', $passages, 2, $dejaAssignesCeCulte
                );

                $choeurP2 = $this->selectionner(
                    $disponibles, 'choeur_p2', $passages, 1, $dejaAssignesCeCulte
                );

                $choeurP3 = $this->selectionner(
                    $disponibles, 'choeur_p3', $passages, 1, $dejaAssignesCeCulte
                );

                $piano1 = $this->selectionner(
                    $disponibles, 'piano1', $passages, 1, $dejaAssignesCeCulte
                );

                $piano2 = $this->selectionner(
                    $disponibles, 'piano2', $passages, 1, $dejaAssignesCeCulte
                );

                $solo = $this->selectionner(
                    $disponibles, 'solo', $passages, 1, $dejaAssignesCeCulte
                );

                $basse = $this->selectionner(
                    $disponibles, 'basse', $passages, 1, $dejaAssignesCeCulte
                );

                $batterie = $this->selectionner(
                    $disponibles, 'batterie', $passages, 1, $dejaAssignesCeCulte
                );

                $programmation[] = [
                    'date'     => $dimanche,
                    'culte'    => $culte,
                    'lead'     => $lead,
                    'choeur'   => [
                        'p1' => $choeurP1,
                        'p2' => $choeurP2,
                        'p3' => $choeurP3,
                    ],
                    'piano1'   => $piano1,
                    'piano2'   => $piano2,
                    'solo'     => $solo,
                    'basse'    => $basse,
                    'batterie' => $batterie,
                ];

                // Ajouter tous ceux assignés ce culte à la liste du dimanche
                $dejaProgrammesCeDimanche = array_merge(
                    $dejaProgrammesCeDimanche,
                    $dejaAssignesCeCulte
                );
            }
        }

        $session->update(['programmation' => $programmation]);

        return redirect()->route('sessions.show', $session)
                        ->with('success', 'Programmation générée avec succès.');
    }

    private function selectionner(
        array $disponibles,
        string $role,
        array &$passages,
        int $nombre,
        array &$dejaAssignes
    ): array {
        // Filtrer éligibles pour ce rôle ET pas encore assignés dans ce culte
        $eligibles = array_filter(
            $disponibles,
            fn($m) => $m[$role] === true && !in_array($m['nom'], $dejaAssignes)
        );

        if (empty($eligibles)) {
            return [];
        }

        // Mélange aléatoire avant le tri
        $eligibles = array_values($eligibles);
        shuffle($eligibles);

        // Tri : passages croissant pour ce rôle, puis score décroissant
        usort($eligibles, function ($a, $b) use ($passages, $role) {
            $passA = $passages[$a['nom']][$role];
            $passB = $passages[$b['nom']][$role];

            if ($passA !== $passB) {
                return $passA - $passB;
            }

            return $b['score'] - $a['score'];
        });

        $selectionnes = array_slice($eligibles, 0, $nombre);

        foreach ($selectionnes as $membre) {
            $passages[$membre['nom']][$role]++;
            // Marquer comme assigné dans ce culte
            $dejaAssignes[] = $membre['nom'];
        }

        return array_column($selectionnes, 'nom');
    }

    private function getDimanches(int $annee, int $mois): array
    {
        $dimanches = [];
        $date = \Carbon\Carbon::create($annee, $mois, 1)->startOfMonth();

        while ($date->dayOfWeek !== 0) {
            $date->addDay();
        }

        while ($date->month === $mois) {
            $dimanches[] = $date->toDateString();
            $date->addWeek();
        }

        return $dimanches;
    }

    private function getMembresDisponibles(
        array $membres,
        array $absences,
        string $dimanche,
        string $culte
    ): array {
        return array_values(array_filter($membres, function ($membre) use ($absences, $dimanche, $culte) {
            if (!in_array($culte, $membre['cultes_autorises'])) {
                return false;
            }
            foreach ($absences as $absence) {
                if ($absence['nom'] === $membre['nom'] &&
                    in_array($dimanche, $absence['dates'])) {
                    return false;
                }
            }
            return true;
        }));
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
}