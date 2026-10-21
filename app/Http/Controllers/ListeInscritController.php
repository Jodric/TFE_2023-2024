<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription; 
use Illuminate\Support\Facades\Response;



class ListeInscritController extends Controller
{
    public function index()
    {
        // Utilisez le modèle Inscription pour accéder à la table "inscriptions"
        // Récupérez les inscrits de la base de données, triés par ID
        $inscrits = Inscription::orderBy('id')->get();
        return view('ws-admin-listeinscrit', compact('inscrits'));
    }

    // supprimer un inscrit coté admin
    public function destroy($id)
    {
        $inscrit = Inscription::findOrFail($id);
        $inscrit->delete();

        return redirect()->route('ws-admin-listeinscrit.index')->with('success', 'Inscrit supprimé avec succès.');
    }


    // supprimer un inscrit coté client
    public function destroy2($id)
    {
        $inscrit = Inscription::findOrFail($id);
        $inscrit->delete();

        return redirect()->route('accueil.show')->with('success', 'Inscrit supprimé avec succès.');
    }


    public function confirmer(Request $request)
    {
        // Récupérer l'ID de l'inscription à partir de la demande
        $inscriptionId = $request->input('id');
    
        // Mettre à jour le statut dans la base de données
        $inscription = Inscription::find($inscriptionId);
        if ($inscription) {
            $inscription->statut = 'confirme';
            $inscription->save();
            // Peut-être renvoyer une réponse JSON pour confirmer la mise à jour
            return response()->json(['message' => 'Statut mis à jour avec succès'], 200);
        } else {
            // Retourner une erreur si l'inscription n'est pas trouvée
            return response()->json(['message' => 'Inscription non trouvée'], 404);
        }
    }
    

    
}
