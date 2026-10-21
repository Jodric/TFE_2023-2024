
@extends('layout')

@section('content__accueil')
<section class="container container__section">
<a href="{{ route('accueil.show') }}">
    <img class="img__logoDwt" src="assets/images/logo-dwt.svg" alt="logo_smartly">
</a>

<h1 class="title title--big">Plus de place disponible ! Enregistrez-vous sur notre Liste d'attente pour avoir une chance de récupérez un panier alimentaire dans notre épicérie</h1>

<div class="container__connexion">
    <h2 class="title title--medium"> Je me connecte</h2>
    <a class="btn__connexion container__connexion--margT" href="{{ route('dates.index') }}">Connexion</a>
</div>
</section>
@endsection