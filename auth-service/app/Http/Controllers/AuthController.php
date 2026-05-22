<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // ─── INSCRIPTION ─────────────────────────────
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'membre',
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Compte créé avec succès',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    // ─── CONNEXION ───────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        return response()->json([
            'message' => 'Connecté avec succès',
            'user'    => auth()->user(),
            'token'   => $token,
        ]);
    }

    // ─── MON PROFIL ──────────────────────────────
    public function me()
    {
        return response()->json(auth()->user());
    }

    // ─── DÉCONNEXION ─────────────────────────────
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Déconnecté avec succès'
        ]);
    }

    // ─── CHANGER LE RÔLE (admin seulement) ───────
    public function changeRole(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return response()->json([
            'message' => 'Rôle mis à jour',
            'user'    => $user,
        ]);
    }
}