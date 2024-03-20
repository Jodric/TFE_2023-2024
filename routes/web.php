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



// Route pour afficher la page de connexion
Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('auth.login');

// Route pour traiter la soumission du formulaire de connexion
Route::post('/connexion', [AuthController::class, 'login']);





use App\Http\Controllers\DatesController;

// Route pour afficher la liste des dates
Route::get('/dates', [DatesController::class, 'index'])->name('dates.index');



Route::view('/welcome', 'welcome')->name('welcome');



Route::get('/', function () {
    return view('welcome');
});

Route::post('/soumettre-formulaire', [InscriptionController::class, 'soumettreFormulaire']);

Route::get('/', [InscriptionController::class, 'welcome']);



// Route::get('/listeattente', [InscritController::class, 'index']);


