<!-- resources/views/dates.blade.php -->
@extends('layout')


@section('content__date')

<section class="container container__section">

    <h1 class="title title--big">plus d'horaire disponible! Pas de panique. Inscrivez vous sur la liste d'attente</h1>

    <p class="livraisonDay container__connexion--margB container__connexion--margT">vous vous inscrivez pour la distribution du: <span>{{ $next_wednesday }}</span> </p>

    <!--
    <ul class="horaire container__connexion--margB container__connexion--margT">

        <li>16:00</li>
        <li>16:30</li>
        <li>17:00</li>
        <li>17:30</li>
        <li>18:00</li>
    </ul>

-->

    <a class="btn__connexion container__connexion--margT" href="{{ route('welcome') }}">S'inscrire sur la liste d'attente</a>
    <a class="btn__connexion container__connexion--margT" href="{{ route('verification.show') }}">déjà inscrire sur la liste d'attente</a>
    @endsection
</section>