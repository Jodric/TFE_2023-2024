<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscription;

class InscriptionController extends Controller
{
    public function soumettreFormulaire(Request $request)
    {
        // Valider les données du formulaire ici si nécessaire

        $inscription = new Inscription([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'email' => $request->input('email'),
            'telephone' => $request->input('telephone'),
        ]);

        $inscription->save();



        
    }




}




