import { createApp } from "vue";
import Liste from "./component/liste.vue";
import { createVfm } from "vue-final-modal";
import "vue-final-modal/style.css";


const vfm = createVfm();

const app = createApp({});
app.component("liste", Liste);
app.use(vfm).mount("#app");


// Ecouteur d'événement pour le bouton "Se retirer"
document.querySelectorAll('.remove-btn').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.id.split('_')[1];
        togglePopup(`popupModal2_${id}`);
    });
});


// Ecouteur d'événement pour le bouton "Non ou oui" dans le popup de suppression
document.querySelectorAll('[id^=cancelBtn_]').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.id.split('_')[1];
        togglePopup(`popupModal2_${id}`);
    });
});

/*
// Ecouteur d'événement pour le bouton "Disponible"
document.querySelectorAll('.dispo-btn').forEach(button => {
    button.addEventListener('click', () => {
      
        togglePopup('popupModal');
       
    });
});
*/


// Ecouteur d'événements pour les boutons "Disponible"
document.querySelectorAll('[id^="confirmDisponibilityBtn_"]').forEach(button => {
    button.addEventListener('click', (event) => {
        // Récupérer l'ID de l'inscription à partir de l'attribut de données HTML
        const inscriptionId = event.currentTarget.dataset.inscritId;
        console.log("ID de l'inscription:", inscriptionId);

        // Mettre à jour le statut dans la base de données
        fetch('/ws-admin-listeinscrit/confirmer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: inscriptionId, // Envoyer l'ID de l'inscription à mettre à jour
                statut: 'confirme'
            })
        })
        .then(response => {
            if (response.ok) {
                // Recharger la page après la mise à jour du statut
                window.location.href = "/confirmer";
            } else {
                console.error('Erreur lors de la mise à jour du statut');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête:', error);
        });
    });
});


// Fonction pour afficher ou masquer le popup en fonction de son état actuel
function togglePopup(id) {
    const popupModal = document.getElementById(id);
    popupModal.style.display = (popupModal.style.display === "block") ? "none" : "block";
}













