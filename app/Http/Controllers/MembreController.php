<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembreController extends Controller
{
  public function index(Request $request)
{
    $query = Membre::query()->orderBy('nom');

    // Recherche par nom
    if ($request->filled('search')) {
        $query->where('nom', 'ilike', '%' . $request->search . '%');
    }

    // Filtre par culte
    if ($request->filled('culte')) {
        $query->whereJsonContains('cultes_autorises', $request->culte);
    }

    // Filtre par rôle
    if ($request->filled('role')) {
        $query->where($request->role, true);
    }

    return Inertia::render('Membres/Index', [
        'membres' => $query->paginate(12)->withQueryString(),
        'search'  => $request->search ?? '',
        'culte'   => $request->culte  ?? '',
        'role'    => $request->role   ?? '',
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