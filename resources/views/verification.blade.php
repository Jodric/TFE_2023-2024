@extends('layout')


@section('content__connexion')

<section class="container container__section">
    <h1 class="title title--big">Vérification de l'utilisateur</h1>

    @if(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('verification.verify') }}" method="POST">
        @csrf
        <label for="prenom">Prénom:</label><br>
        <input type="text" id="prenom" name="prenom"><br>

        <label for="nom">Nom:</label><br>
        <input type="text" id="nom" name="nom"><br>

        <label for="telephone">Numéro de téléphone:</label><br>
        <input type="text" id="telephone" name="telephone"><br><br>

        <button type="submit">Valider</button>
    </form>
</section>

@endsection