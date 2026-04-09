<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembreController extends Controller
{
    public function index()
    {
        return Inertia::render('Membres/Index', [
            'membres' => Membre::orderBy('nom')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Membres/Form', [
            'membre' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'              => 'required|string|max:100',
            'cultes_autorises' => 'required|array|min:1',
            'cultes_autorises.*' => 'in:C1,C2',
            'lead'             => 'boolean',
            'choeur_p1'        => 'boolean',
            'choeur_p2'        => 'boolean',
            'choeur_p3'        => 'boolean',
            'piano1'           => 'boolean',
            'piano2'           => 'boolean',
            'solo'             => 'boolean',
            'basse'            => 'boolean',
            'batterie'         => 'boolean',
            'score'            => 'required|integer|min:1|max:10',
        ]);

        Membre::create($validated);

        return redirect()->route('membres.index')->with('success', 'Membre ajouté avec succès.');
    }

    public function edit(Membre $membre)
    {
        return Inertia::render('Membres/Form', [
            'membre' => $membre,
        ]);
    }

    public function update(Request $request, Membre $membre)
    {
        $validated = $request->validate([
            'nom'              => 'required|string|max:100',
            'cultes_autorises' => 'required|array|min:1',
            'cultes_autorises.*' => 'in:C1,C2',
            'lead'             => 'boolean',
            'choeur_p1'        => 'boolean',
            'choeur_p2'        => 'boolean',
            'choeur_p3'        => 'boolean',
            'piano1'           => 'boolean',
            'piano2'           => 'boolean',
            'solo'             => 'boolean',
            'basse'            => 'boolean',
            'batterie'         => 'boolean',
            'score'            => 'required|integer|min:1|max:10',
        ]);

        $membre->update($validated);

        return redirect()->route('membres.index')->with('success', 'Membre mis à jour.');
    }

    public function destroy(Membre $membre)
    {
        $membre->delete();

        return redirect()->route('membres.index')->with('success', 'Membre supprimé.');
    }
}