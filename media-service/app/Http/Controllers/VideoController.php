<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    // ─── UPLOAD UNE VIDÉO ────────────────────────
    public function upload(Request $request)
    {
        $request->validate([
            'video'        => 'required|file|mimes:mp4,mov,avi|max:512000',
            'title'        => 'required|string',
            'professor_id' => 'required|integer',
        ]);

        $file     = $request->file('video');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Stocke dans storage/app/public/videos/
        $path = $file->storeAs('videos', $filename, 'public');

        // URL publique accessible depuis le navigateur
        $url = url('storage/' . $path);

        $video = Video::create([
            'title'        => $request->title,
            'filename'     => $filename,
            'path'         => $path,
            'url'          => $url,
            'size'         => $file->getSize(),
            'professor_id' => $request->professor_id,
            'status'       => 'ready',
        ]);

        return response()->json([
            'message' => 'Vidéo uploadée avec succès',
            'video'   => $video,
        ], 201);
    }

    // ─── LISTE DES VIDÉOS ────────────────────────
    public function index()
    {
        $videos = Video::all();
        return response()->json($videos);
    }

    // ─── DÉTAIL D'UNE VIDÉO ──────────────────────
    public function show($id)
    {
        $video = Video::findOrFail($id);
        return response()->json($video);
    }

    // ─── SUPPRIMER UNE VIDÉO ─────────────────────
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        Storage::disk('public')->delete($video->path);
        $video->delete();

        return response()->json([
            'message' => 'Vidéo supprimée'
        ]);
    }

    // ─── TÉLÉCHARGEMENT ──────────────────────────
    public function download($id)
    {
        $video = Video::findOrFail($id);
        $path  = storage_path('app/public/' . $video->path);

        return response()->download($path, $video->filename);
    }
}