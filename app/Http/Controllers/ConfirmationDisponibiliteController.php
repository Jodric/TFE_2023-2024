<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscription;

class ConfirmationDisponibiliteController extends Controller
{
    

public function confirm($id)
{
    // Trouver l'inscription correspondante dans la base de données
    $inscrit = Inscription::findOrFail($id);

    // Mettre à jour le statut du client en fonction de la confirmation de disponibilité
    $inscrit->statut = 'disponible'; // Vous pouvez définir le statut comme vous le souhaitez

    // Enregistrez les modifications dans la base de données
    $inscrit->save();

    // Rediriger l'utilisateur vers une page appropriée
    return redirect()->route('ws-admin-listeinscrit.index')->with('success', 'Statut du client mis à jour avec succès.');
}

}
