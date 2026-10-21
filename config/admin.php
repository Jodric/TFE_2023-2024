<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Compte administrateur
    |--------------------------------------------------------------------------
    |
    | Identifiants de connexion à l'espace administrateur (/ws-admin).
    | Ils sont définis dans le fichier .env, qui n'est pas envoyé sur git.
    |
    */

    'email' => env('ADMIN_EMAIL'),

    'password' => env('ADMIN_PASSWORD'),

];
