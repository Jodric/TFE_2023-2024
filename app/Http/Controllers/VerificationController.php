<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscription;

class VerificationController extends Controller
{
    public function showForm()
    {
        return view('verification');
    }

    public function verifyUser(Request $request)
    {
        // Récupérer les données du formulaire
        $prenom = $request->input('prenom');
        $nom = $request->input('nom');
        $telephone = $request->input('telephone');

        // Vérifier si l'utilisateur existe dans la base de données
        $inscrit = Inscription::where('prenom', $prenom)
                                  ->where('nom', $nom)
                                  ->where('telephone', $telephone)
                                  ->first();

        if ($inscrit) {
            // L'utilisateur existe, rediriger vers une autre vue
            return view('profil_verifie', ['inscrit' => $inscrit]);
        } else {
            // L'utilisateur n'existe pas, afficher un message d'erreur
            return redirect()->back()->with('error', 'Utilisateur non trouvé.');
        }
    }

    // supprimer un inscrit coté client
    public function destroy2($id)
    {
        $inscrit = Inscription::findOrFail($id);
        $inscrit->delete();

        return redirect()->route('accueil.show')->with('success', 'Inscrit supprimé avec succès.');
    }

    
    
}
