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

        // Compteur par rôle et par membre
        $passages = [];
        foreach ($membres as $m) {
            $passages[$m['nom']] = [
                'lead_c1'      => 0,
                'lead_c2'      => 0,
                'choeur_sopra' => 0,
                'choeur_alto'  => 0,
                'choeur_tenor' => 0,
                'piano1'       => 0,
                'piano2'       => 0,
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

                // Exclure ceux déjà programmés ce dimanche
                $disponibles = array_values(array_filter(
                    $disponibles,
                    fn($m) => !in_array($m['nom'], $dejaCeDimanche)
                ));

                $dejaCeCulte = [];

                // Le rôle lead dépend du culte
                $roleLeadKey = $culte === 'C1' ? 'lead_c1' : 'lead_c2';

                $lead = $this->selectionner($disponibles, $roleLeadKey, $passages, 1, $dejaCeCulte);

                $sopra = $this->selectionner($disponibles, 'choeur_sopra', $passages, 2, $dejaCeCulte);
                $alto  = $this->selectionner($disponibles, 'choeur_alto',  $passages, 1, $dejaCeCulte);
                $tenor = $this->selectionner($disponibles, 'choeur_tenor', $passages, 1, $dejaCeCulte);

                $piano1   = $this->selectionner($disponibles, 'piano1',   $passages, 1, $dejaCeCulte);
                $piano2   = $this->selectionner($disponibles, 'piano2',   $passages, 1, $dejaCeCulte);
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

    public function modifier(Request $request, SessionMensuelle $session)
    {
        $request->validate([
            'programmation' => 'required|array',
        ]);

        $session->update(['programmation' => $request->programmation]);

        return back()->with('success', 'Programmation mise à jour.');
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

    // ── Helpers ─────────────────────────────────────────────────────────────

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
        $eligibles = array_filter($disponibles, fn($m) => ($m[$role] ?? false) === true && !in_array($m['nom'], $dejaAssignes));

        if (empty($eligibles)) return [];

        $eligibles = array_values($eligibles);
        shuffle($eligibles);

        usort($eligibles, function ($a, $b) use ($passages, $role) {
            $diff = $passages[$a['nom']][$role] - $passages[$b['nom']][$role];
            return $diff !== 0 ? $diff : $b['score'] - $a['score'];
        });

        $selectionnes = array_slice($eligibles, 0, $nombre);

        foreach ($selectionnes as $m) {
            $passages[$m['nom']][$role]++;
            $dejaAssignes[] = $m['nom'];
        }

        return array_column($selectionnes, 'nom');
    }
}