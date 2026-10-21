<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Inscrits</title>
   
    

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <h3>Il y a actuellement {{ $inscrits->count() }} personnes en attente</h3>

    <h1>Liste des Inscrits</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Numéro de Téléphone</th>
                <th>Action</th>
                <th>satut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscrits as $inscrit)
            <tr>
                <td>{{ $inscrit->id }}</td>
                <td>{{ $inscrit->nom }}</td>
                <td>{{ $inscrit->prenom }}</td>
                <td>{{ $inscrit->telephone }}</td>
                <td>{{ $inscrit->statut }}</td>
                <td>
                    <button class="remove-btn" id="removeBtn_{{ $inscrit->id }}">Se retirer</button>
                    <!-- bouton Disponible -->
                    
                    <!-- <button class="dispo-btn" id="availableBtn_{{ $inscrit->id }}" data-nom="{{ $inscrit->nom }}">Disponible</button>-->
                <!-- Bouton "disponible" dans la vue administrateur -->
                    <button class="" id="confirmDisponibilityBtn_{{ $inscrit->id }}" data-inscrit-id="{{ $inscrit->id }}">Disponible</button>

                    <!-- Popup pour confirmer la suppression -->
                    <div class="popup-modal" id="popupModal2_{{ $inscrit->id }}">
                        <p>Voulez-vous vraiment vous retirer ?</p>
                        <button id="cancelBtn_{{ $inscrit->id }}">Non</button>
                        <!-- Formulaire pour supprimer l'inscrit -->
                        <form id="deleteForm_{{ $inscrit->id }}" action="{{ route('inscrits.destroy', $inscrit->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Oui</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


 


    
   
</body>

</html>
