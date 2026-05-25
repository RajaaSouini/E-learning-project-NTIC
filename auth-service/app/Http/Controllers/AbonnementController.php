<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\User;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    private $plans = [
        'mensuel' => ['prix' => 9.99,  'duree_jours' => 30],
        'annuel'  => ['prix' => 79.99, 'duree_jours' => 365],
    ];

    public function souscrire(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'plan'    => 'required|in:mensuel,annuel',
        ]);

        $plan = $this->plans[$request->plan];
        $user = User::findOrFail($request->user_id);

        Abonnement::where('user_id', $request->user_id)
            ->where('statut', 'actif')
            ->update(['statut' => 'annule']);

        $abonnement = Abonnement::create([
            'user_id'   => $request->user_id,
            'plan'      => $request->plan,
            'prix'      => $plan['prix'],
            'statut'    => 'actif',
            'debut_le'  => now(),
            'expire_le' => now()->addDays($plan['duree_jours']),
        ]);

        $user->update(['role' => 'premium']);

        return response()->json([
            'message'    => 'Abonnement activé !',
            'abonnement' => $abonnement,
            'expire_le'  => $abonnement->expire_le->format('d/m/Y'),
        ], 201);
    }

    public function statut($userId)
    {
        $abonnement = Abonnement::where('user_id', $userId)
            ->where('statut', 'actif')
            ->latest()
            ->first();

        if (!$abonnement || $abonnement->expire_le < now()) {
            return response()->json(['premium' => false]);
        }

        return response()->json([
            'premium'        => true,
            'plan'           => $abonnement->plan,
            'prix'           => $abonnement->prix,
            'expire_le'      => $abonnement->expire_le->format('d/m/Y'),
            'jours_restants' => now()->diffInDays($abonnement->expire_le),
        ]);
    }

    public function annuler($userId)
    {
        Abonnement::where('user_id', $userId)
            ->where('statut', 'actif')
            ->update(['statut' => 'annule']);

        User::where('id', $userId)->update(['role' => 'membre']);

        return response()->json(['message' => 'Abonnement annulé']);
    }

    public function index()
    {
        $abonnements = Abonnement::with('user')->latest()->get();
        return response()->json($abonnements);
    }
}