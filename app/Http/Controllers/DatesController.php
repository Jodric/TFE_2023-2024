<?php

// DatesController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; 

class DatesController extends Controller
{
    public function index()
    {
      
        $current_date = Carbon::now();

        // source https://carbon.nesbot.com/symfony/
        
        while ($current_date->dayOfWeek != Carbon::WEDNESDAY) {
            $current_date->addDay();
        }

        
        $next_wednesday_str = $current_date->formatLocalized('mercredi %d %B %Y');

        
        return view('dates', ['next_wednesday' => $next_wednesday_str]);
    }
}


