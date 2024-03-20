<?php

// AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('connexion');
    }

    public function login(Request $request)
    {
        // Vérifier les informations d'identification de l'utilisateur ici
        $credentials = $request->only('email', 'password');

        if ($credentials['email'] === 'admin@heaj.be' && $credentials['password'] === 'admin') {
            // Authentification réussie, rediriger vers la liste des dates
            return redirect()->route('dates.index');
        } else {
            // Authentification échouée, rediriger vers la page de connexion avec un message d'erreur
            return redirect()->back()->withErrors(['error' => 'Adresse e-mail ou mot de passe incorrect.']);
        }
    }
}

