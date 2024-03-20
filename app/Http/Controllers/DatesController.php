<?php

// DatesController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatesController extends Controller
{
    public function index()
    {
        // Définit les dates (dans ce cas, il y a une seule date)
        $dates = [
            'mercredi 20 mars 2024' => 'mercredi-20-mars-2024',
        ];

        // Passe les dates à la vue et affiche la vue des dates
        return view('dates', ['dates' => $dates]);
    }
}
