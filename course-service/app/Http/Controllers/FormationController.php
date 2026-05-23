<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormationController extends Controller
{
    // Liste toutes les formations avec chapitres et cours
    public function index()
    {
        $formations = Formation::with(['chapters.courses'])->get();
        return response()->json($formations);
    }

    // Détail d'une formation
    public function show($id)
    {
        $formation = Formation::with(['chapters.courses'])->findOrFail($id);
        return response()->json($formation);
    }

    // Créer une formation
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

    // Ajouter un chapitre à une formation
    public function addChapter(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        $chapter = Chapter::create([
            'title'        => $request->title,
            'order'        => $request->order ?? 1,
            'formation_id' => $id,
        ]);

        return response()->json([
            'message' => 'Chapitre ajouté',
            'chapter' => $chapter,
        ], 201);
    }

    // Ajouter un cours à un chapitre
    public function addCourseToChapter(Request $request, $formationId, $chapterId)
    {
        $request->validate([
            'title'        => 'required|string',
            'description'  => 'nullable|string',
            'technology'   => 'required|string',
            'level'        => 'required|in:debutant,intermediaire,avance',
            'duration'     => 'nullable|integer',
            'video_url'    => 'nullable|string',
            'professor_id' => 'required|integer',
        ]);

        $course = Course::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'slug'         => Str::slug($request->title) . '-' . uniqid(),
            'technology'   => $request->technology,
            'level'        => $request->level,
            'duration'     => $request->duration ?? 0,
            'video_url'    => $request->video_url,
            'professor_id' => $request->professor_id,
            'chapter_id'   => $chapterId,
            'status'       => 'publie',
        ]);

        return response()->json([
            'message' => 'Cours ajouté au chapitre',
            'course'  => $course,
        ], 201);
    }

    // Supprimer une formation
    public function destroy($id)
    {
        $formation = Formation::findOrFail($id);
        $formation->delete();

        return response()->json(['message' => 'Formation supprimée']);
    }
}