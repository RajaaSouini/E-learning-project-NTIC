<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    // ─── LISTE TOUS LES COURS ────────────────────
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses);
    }

    // ─── DÉTAIL D'UN COURS ───────────────────────
    public function show($id)
{
    $course = Course::with('chapter.formation')->findOrFail($id);
    return response()->json($course);
}

    // ─── CRÉER UN COURS (professeur/admin) ───────
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
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
            'slug'         => Str::slug($request->title),
            'technology'   => $request->technology,
            'level'        => $request->level,
            'duration'     => $request->duration ?? 0,
            'video_url'    => $request->video_url,
            'professor_id' => $request->professor_id,
            'status'       => 'brouillon',
        ]);

        return response()->json([
            'message' => 'Cours créé avec succès',
            'course'  => $course,
        ], 201);
    }

    // ─── MODIFIER UN COURS ───────────────────────
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($request->all());

        return response()->json([
            'message' => 'Cours mis à jour',
            'course'  => $course,
        ]);
    }

    // ─── PUBLIER UN COURS (admin) ─────────────────
    public function publish($id)
    {
        $course = Course::findOrFail($id);
        $course->update(['status' => 'publie']);

        return response()->json([
            'message' => 'Cours publié',
            'course'  => $course,
        ]);
    }

    // ─── SUPPRIMER UN COURS ──────────────────────
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json([
            'message' => 'Cours supprimé'
        ]);
    }

    // ─── FILTRER PAR TECHNOLOGIE / NIVEAU ────────
    public function filter(Request $request)
    {
        $query = Course::where('status', 'publie');

        if ($request->technology) {
            $query->where('technology', $request->technology);
        }

        if ($request->level) {
            $query->where('level', $request->level);
        }

        return response()->json($query->get());
    }
    // ─── Changer le statut premium d'un cours ────────
public function togglePremium($id)
{
    $course = Course::findOrFail($id);
    $course->update([
        'is_premium' => !$course->is_premium
    ]);

    return response()->json([
        'message'    => $course->is_premium ? 'Cours marqué Premium' : 'Cours marqué Gratuit',
        'is_premium' => $course->is_premium,
        'course'     => $course,
    ]);
}
}