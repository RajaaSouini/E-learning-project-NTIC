<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Formation;
use App\Models\Cursus;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->q;

        if (!$query || strlen($query) < 2) {
            return response()->json([
                'courses'    => [],
                'formations' => [],
                'cursus'     => [],
            ]);
        }

        try {
            $courses = Course::where('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->orWhere('technology',  'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'title', 'technology', 'level', 'duration']);

            $formations = Formation::where('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get(['id', 'title', 'description']);

            $cursus = Cursus::where('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get(['id', 'title', 'description']);

            return response()->json([
                'courses'    => $courses,
                'formations' => $formations,
                'cursus'     => $cursus,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => $e->getMessage(),
                'courses' => [],
                'formations' => [],
                'cursus'  => [],
            ], 500);
        }
    }
}