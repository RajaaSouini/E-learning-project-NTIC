<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormationController extends Controller
{
    // ─── LISTE TOUTES LES FORMATIONS ─────────────
    public function index()
    {
        $formations = Formation::with('chapters')->get();
        return response()->json($formations);
    }

    // ─── DÉTAIL D'UNE FORMATION ──────────────────
    public function show($id)
    {
        $formation = Formation::with('chapters')->findOrFail($id);
        return response()->json($formation);
    }

    // ─── CRÉER UNE FORMATION ─────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'professor_id' => 'required|integer',
        ]);

        $formation = Formation::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'slug'         => Str::slug($request->title),
            'professor_id' => $request->professor_id,
            'status'       => 'brouillon',
        ]);

        return response()->json([
            'message'   => 'Formation créée avec succès',
            'formation' => $formation,
        ], 201);
    }

    // ─── MODIFIER UNE FORMATION ──────────────────
    public function update(Request $request, $id)
    {
        $formation = Formation::findOrFail($id);
        $formation->update($request->all());

        return response()->json([
            'message'   => 'Formation mise à jour',
            'formation' => $formation,
        ]);
    }

    // ─── AJOUTER UN CHAPITRE ─────────────────────
    public function addChapter(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'order' => 'required|integer',
        ]);

        $chapter = Chapter::create([
            'title'        => $request->title,
            'order'        => $request->order,
            'formation_id' => $id,
        ]);

        return response()->json([
            'message' => 'Chapitre ajouté',
            'chapter' => $chapter,
        ], 201);
    }

    // ─── SUPPRIMER UNE FORMATION ─────────────────
    public function destroy($id)
    {
        $formation = Formation::findOrFail($id);
        $formation->delete();

        return response()->json([
            'message' => 'Formation supprimée'
        ]);
    }
}