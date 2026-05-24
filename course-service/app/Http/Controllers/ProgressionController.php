<?php

namespace App\Http\Controllers;

use App\Models\Progression;
use App\Models\Formation;
use App\Models\Course;
use Illuminate\Http\Request;

class ProgressionController extends Controller
{
    // ─── Marquer un cours comme vu ───────────────
    public function markAsWatched(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|integer',
            'course_id'    => 'required|integer',
            'formation_id' => 'nullable|integer',
            'watch_time'   => 'nullable|integer',
        ]);

        $progression = Progression::updateOrCreate(
            [
                'user_id'   => $request->user_id,
                'course_id' => $request->course_id,
            ],
            [
                'formation_id' => $request->formation_id,
                'completed'    => true,
                'watch_time'   => $request->watch_time ?? 0,
            ]
        );

        return response()->json([
            'message'     => 'Progression enregistrée',
            'progression' => $progression,
        ]);
    }

    // ─── Progression d'un user dans une formation ─
    public function getFormationProgress($userId, $formationId)
    {
        $formation = Formation::with('chapters.courses')->findOrFail($formationId);

        // Total des cours dans la formation
        $totalCours = 0;
        foreach ($formation->chapters as $chapter) {
            $totalCours += $chapter->courses->count();
        }

        if ($totalCours === 0) {
            return response()->json([
                'pourcentage'    => 0,
                'cours_vus'      => 0,
                'total_cours'    => 0,
                'completed'      => false,
            ]);
        }

        // Cours vus par l'utilisateur dans cette formation
        $coursVus = Progression::where('user_id', $userId)
            ->where('formation_id', $formationId)
            ->where('completed', true)
            ->count();

        $pourcentage = round(($coursVus / $totalCours) * 100);

        return response()->json([
            'pourcentage' => $pourcentage,
            'cours_vus'   => $coursVus,
            'total_cours' => $totalCours,
            'completed'   => $pourcentage === 100,
            'formation'   => $formation->title,
        ]);
    }

    // ─── Tous les cours vus par un user ──────────
    public function getUserProgress($userId)
    {
        $progressions = Progression::where('user_id', $userId)
            ->where('completed', true)
            ->get();

        return response()->json($progressions);
    }

    // ─── Stats pour professeur/admin ─────────────
    public function getCourseStats($courseId)
    {
        $total = Progression::where('course_id', $courseId)
            ->where('completed', true)
            ->count();

        return response()->json([
            'course_id'      => $courseId,
            'total_etudiants' => $total,
        ]);
    }

    public function getFormationStats($formationId)
    {
        $formation   = Formation::with('chapters.courses')->findOrFail($formationId);

        // Nombre d'étudiants distincts qui ont au moins 1 cours vu
        $etudiants = Progression::where('formation_id', $formationId)
            ->where('completed', true)
            ->distinct('user_id')
            ->count('user_id');

        // Nombre d'étudiants ayant terminé la formation (100%)
        $totalCours = 0;
        foreach ($formation->chapters as $ch) {
            $totalCours += $ch->courses->count();
        }

        $termines = 0;
        if ($totalCours > 0) {
            // Compte les users qui ont vu TOUS les cours
            $usersProgress = Progression::where('formation_id', $formationId)
                ->where('completed', true)
                ->selectRaw('user_id, COUNT(*) as total')
                ->groupBy('user_id')
                ->having('total', '>=', $totalCours)
                ->count();
            $termines = $usersProgress;
        }

        return response()->json([
            'formation_id'   => $formationId,
            'formation_title' => $formation->title,
            'total_etudiants' => $etudiants,
            'termines'        => $termines,
            'total_cours'     => $totalCours,
        ]);
    }
}