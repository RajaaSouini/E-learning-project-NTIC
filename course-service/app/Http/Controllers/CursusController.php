<?php

namespace App\Http\Controllers;

use App\Models\Cursus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CursusController extends Controller
{
    // ─── LISTE TOUS LES CURSUS ───────────────────
    public function index()
    {
        $cursus = Cursus::all();
        return response()->json($cursus);
    }

    // ─── DÉTAIL D'UN CURSUS ──────────────────────
    public function show($id)
    {
        $cursus = Cursus::findOrFail($id);
        return response()->json($cursus);
    }

    // ─── CRÉER UN CURSUS (admin) ─────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'level'       => 'required|string',
            'goal'        => 'nullable|string',
        ]);

        $cursus = Cursus::create([
            'title'       => $request->title,
            'description' => $request->description,
            'slug'        => Str::slug($request->title),
            'level'       => $request->level,
            'goal'        => $request->goal,
        ]);

        return response()->json([
            'message' => 'Cursus créé avec succès',
            'cursus'  => $cursus,
        ], 201);
    }

    // ─── MODIFIER UN CURSUS ──────────────────────
    public function update(Request $request, $id)
    {
        $cursus = Cursus::findOrFail($id);
        $cursus->update($request->all());

        return response()->json([
            'message' => 'Cursus mis à jour',
            'cursus'  => $cursus,
        ]);
    }

    // ─── SUPPRIMER UN CURSUS ─────────────────────
    public function destroy($id)
    {
        $cursus = Cursus::findOrFail($id);
        $cursus->delete();

        return response()->json([
            'message' => 'Cursus supprimé'
        ]);
    }
}