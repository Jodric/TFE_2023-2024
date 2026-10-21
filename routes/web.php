<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\AuthController;
//use App\Http\Controllers\InscritController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// route layout
Route::get('/layout', function () {
    return view('layout'); 
});


// route pour l'accueil
use App\Http\Controllers\AccueilController;

Route::get('/accueil', [AccueilController::class, 'showAccueil'])->name('accueil.show');

// Route pour afficher la page de connexion
Route::get('/ws-admin', [AuthController::class, 'showLoginForm'])->name('ws-admin');

// Route pour traiter la soumission du formulaire de connexion
Route::post('/ws-admin', [AuthController::class, 'login']);





use App\Http\Controllers\DatesController;

// Route pour afficher la liste des dates
Route::get('/dates', [DatesController::class, 'index'])->name('dates.index');



Route::view('/welcome', 'welcome')->name('welcome');



Route::get('/', function () {
    return view('welcome');
});

Route::post('/soumettre-formulaire', [InscriptionController::class, 'soumettreFormulaire']);



// Route::get('/listeattente', [InscritController::class, 'index']);

use App\Http\Controllers\ListeInscritController;

Route::get('/ws-admin-listeinscrit', [ListeInscritController::class, 'index']) ->name('ws-admin-listeinscrit.index');;

// route pour supprimer les inscrits coté admin
Route::delete('/ws-admin-listeinscrit/{id}', [ListeInscritController::class, 'destroy'])->name('inscrits.destroy');


// route pour supprimer les inscrits coté client
Route::delete('/ws-client-listeinscrit/{id}', [ListeInscritController::class, 'destroy2'])->name('inscrits.destroy2');


// route pour l'envoie vers la page de confirmation
use App\Http\Controllers\ConfirmationController;

Route::get('/confirmer', [ConfirmationController::class, 'showConfirmation'])->name('confirmation.show');



// route pour l'envoie de notification pour le prochain inscrit
Route::get('/api/prochain-inscrit', [InscriptionController::class, 'prochainInscrit']);


// route pour verifier si un utilisateur existe déjà

use App\Http\Controllers\VerificationController;

Route::get('/verification', [VerificationController::class, 'showForm'])->name('verification.show');

// route pour afficher le profil de l'utilisateur verifié
Route::post('/verification', [VerificationController::class, 'verifyUser'])->name('verification.verify');


// gestion de la notification


// sen notif disponible
use App\Http\Controllers\ConfirmationDisponibiliteController;

Route::post('/confirmation-disponibilite/{id}', [ConfirmationDisponibiliteController::class, 'confirm'])->name('confirmation.disponibilite.confirm');


// confir le statut dans la bd
Route::post('/ws-admin-listeinscrit/confirmer', [ListeInscritController::class, 'confirmer']);

// surveille les changements dans la bd
Route::get('/get-inscriptions-status', [InscriptionController::class, 'getInscriptionsStatus']);



/*

//  gérer l'envoi de la notification push
Route::post('/envoyer-notification/{id}', 'NotificationController@envoyerNotification')->name('envoyer.notification');


// route pour envoyer la notification
Route::post('/envoyer-notification/{id}', [ListeInscritController::class, 'envoyerNotification'])->name('envoyer.notification');




// route pour mettre le serveur sse en place

Route::get('/sse', function () {
    return response()->stream(function () {
        while (true) {
            // Envoyer un événement SSE avec des données
            echo "data: " . json_encode(['message' => 'Nouvelle notification']) . "\n\n";
            flush();

            // Attendre quelques secondes avant d'envoyer le prochain événement
            sleep(5);
        }
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
});

*/