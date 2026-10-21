@extends('layout')


@section('content__connexion')

<section class="container container__section">
    <h1 class="title title--big">Connectez-vous</h1>
    <form class="container__connexion" action="{{ route('ws-admin') }}" method="post">
        @csrf
        <label class="container__connexion--margB " for="email">Adresse Email :</label>
        <input class="container__connexion--margB " type="email" id="email" name="email" required>

        <label class="container__connexion--margB container__connexion--margT" for="password">Mot de passe :</label>
        <input class="container__connexion--margB" type="password" id="password" name="password" required>

        <button class="btn__connexion " type="submit">Connexion</button>
    </form>

    <div>
        @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                <li class="containair__error">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</section>

@endsection