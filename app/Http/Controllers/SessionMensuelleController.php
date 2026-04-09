<?php

namespace App\Http\Controllers;

use App\Models\SessionMensuelle;
use App\Models\Membre;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SessionMensuelleController extends Controller
{
    public function index()
    {
        return Inertia::render('Sessions/Index', [
            'sessions' => SessionMensuelle::orderByDesc('annee')
                                          ->orderByDesc('mois')
                                          ->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Sessions/Form', [
            'membres' => Membre::orderBy('nom')->get(),
            'session' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee'              => 'required|integer|min:2024',
            'mois'               => 'required|integer|min:1|max:12',
            'absences'           => 'nullable|array',
            'absences.*.nom'     => 'required|string',
            'absences.*.dates'   => 'required|array',
            'absences.*.dates.*' => 'date',
        ]);

        // Vérifier qu'une session n'existe pas déjà pour ce mois
        $existe = SessionMensuelle::where('annee', $validated['annee'])
                                  ->where('mois', $validated['mois'])
                                  ->exists();

        if ($existe) {
            return back()->withErrors([
                'mois' => 'Une session existe déjà pour ce mois.',
            ]);
        }

        SessionMensuelle::create($validated);

        return redirect()->route('sessions.index')->with('success', 'Session créée.');
    }

    public function show(SessionMensuelle $session)
    {
        return Inertia::render('Sessions/Show', [
            'session' => $session,
        ]);
    }

    public function destroy(SessionMensuelle $session)
    {
        $session->delete();

        return redirect()->route('sessions.index')->with('success', 'Session supprimée.');
    }
}