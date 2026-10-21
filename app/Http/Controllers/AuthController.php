<?php

// AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('ws-admin');
    }

    public function login(Request $request)
    {
        // Vérifier les informations d'identification de l'utilisateur ici
        $credentials = $request->only('email', 'password');

        // Identifiants admin définis dans le fichier .env (voir config/admin.php)
        $adminEmail = config('admin.email');
        $adminPassword = config('admin.password');

        if ($adminEmail && $adminPassword
            && ($credentials['email'] ?? null) === $adminEmail
            && ($credentials['password'] ?? null) === $adminPassword) {
            // Authentification réussie, rediriger vers la liste des dates
            return redirect()->route('ws-admin-listeinscrit.index');
        } else {
            // Authentification échouée, rediriger vers la page de connexion avec un message d'erreur
            return redirect()->back()->withErrors(['error' => 'Adresse e-mail ou mot de passe incorrect.']);
        }
    }
}

