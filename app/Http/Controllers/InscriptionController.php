<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscription;

class InscriptionController extends Controller
{
    public function soumettreFormulaire(Request $request)
    {
        // Valider les données du formulaire 

        $inscription = new Inscription([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'email' => $request->input('email'),
            'telephone' => $request->input('telephone'),
            'statut' => 'en_attente',
            'rdv' => 'en_attente',
            
        ]);

        $inscription->save();


        
    }




    public function getInscriptionsStatus()
    {
        $inscriptions = Inscription::all();
        return response()->json($inscriptions);
    }
    




}






