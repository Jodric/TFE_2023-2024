@extends('layout')


@section('title', 'Succès de la vérification')

@section('content__profil')

<section class="container container__section">

    <h1 class="title title--big">Bienvenue sur votre profil</h1>

    <div class="container__connexion profil">
        <div class="statut profil__infos">
            <p>Votre ID : {{ $inscrit->id }}</p>
            <p>Nom : {{ $inscrit->nom }}</p>
            <p>Prénom : {{ $inscrit->prenom }}</p>
            <p>Numéro de téléphone : {{ $inscrit->telephone }}</p>
            <p>Statut : {{ $inscrit->statut }}</p>
        </div>

        <button class="remove-btn btn__connexion btn__connexion--retirer" id="removeBtn_{{ $inscrit->id }}">Se retirer</button>
    </div>

    <!-- <div id="inscription_{{ $inscrit->id }}"></div>-->


    <!-- popup pour confirmer la suppression -->
    <div class="popup-modal profil__popup" id="popupModal2_{{ $inscrit->id }}">
        <h2>Confirmation</h2>
        <p>Voulez-vous vraiment vous retirer ?</p>

        <div class="profil__popup-actions">
            <button class="btn__connexion btn__connexion--annuler" id="cancelBtn_{{ $inscrit->id }}">Non</button>

            <form id="deleteForm_{{ $inscrit->id }}" action="{{ route('inscrits.destroy2', $inscrit->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn__connexion btn__connexion--retirer" type="submit">Oui</button>
            </form>
        </div>
    </div>


    <!--fenêtre pop-up pour confirmer la disponibilité -->
    <div class="popup-modal profil__popup" id="confirmationModal">
        <p>Voulez-vous confirmer votre disponibilité pour récupérer un panier alimentaire ?</p>

        <div class="profil__popup-actions">
            <button class="btn__connexion" id="confirmDisponibilityBtn">Confirmer</button>
            <button class="btn__connexion btn__connexion--annuler" id="cancelDisponibilityBtn">Annuler</button>
        </div>
    </div>

</section>

<script>
// Variable pour suivre les inscriptions confirmées
let inscriptionsConfirmees = [];

// Fonction pour récupérer le statut des inscriptions depuis le serveur
function fetchInscriptionsStatus() {
    fetch('/get-inscriptions-status')
        .then(response => response.json())
        .then(data => {
            // Filtrer les inscriptions confirmées qui n'ont pas encore été notifiées
            const nouvellesInscriptionsConfirmees = data.filter(inscription => inscription.statut === 'confirme' && !inscriptionsConfirmees.includes(inscription.id));

            // Envoyer les notifications pour les nouvelles inscriptions confirmées
            nouvellesInscriptionsConfirmees.forEach(inscription => {
                envoyer_notification_confirmation(inscription.nom);
                inscriptionsConfirmees.push(inscription.id);
            });
        })
        .catch(error => console.error('Erreur lors de la récupération du statut des inscriptions:', error));
}

// Appeler la fonction fetchInscriptionsStatus() toutes les 5 secondes (par exemple)
setInterval(fetchInscriptionsStatus, 5000); // 5000 millisecondes = 5 secondes


// notification push

// Fonction pour envoyer une notification avec le nom de l'utilisateur
function envoyer_notification_confirmation(nom) {
    Push.create("Invitation à récupérer un panier alimentaire", {
        body: "Bonjour " + nom + ", vous êtes invité à venir chercher un panier alimentaire.",
        icon: "/img/icon/icone.png",
        timeout: 0, 
        onClick: function () {
            window.focus();
            this.close();
            // Actions à effectuer lorsque l'utilisateur clique sur la notification
            togglePopup('confirmationModal'); 
        }, 
    });
}




// Ecouteur d'événements pour le bouton "Confirmer" dans le popup de confirmation

function toggleTextColor() {
    const bg = document.querySelectorAll('.statut'); 
    bg.forEach(element => {
        element.classList.toggle('bg__statut'); 
    });
}

function toggleTextColor2() {
    const bgR = document.querySelectorAll('.statut'); 
    bgR.forEach(elementR => {
        elementR.classList.toggle('bg__statut--refus'); 
    });
}

document.getElementById('confirmDisponibilityBtn').addEventListener('click', () => {
   
    toggleTextColor();
    togglePopup('confirmationModal');
    

});

// Ecouteur d'événements pour le bouton "Annuler" dans le popup de confirmation
document.getElementById('cancelDisponibilityBtn').addEventListener('click', () => {
   
    toggleTextColor2();
   togglePopup('confirmationModal');
});



// Fonction pour afficher ou masquer le popup en fonction de son état actuel
function togglePopup(id) {
    const popupModal = document.getElementById(id);
    popupModal.style.display = (popupModal.style.display === "block") ? "none" : "block";
}


</script>

@endsection