<template>
  <div>
    <h2>Inscription à la Liste d'Attente</h2>
    <form @submit.prevent="submitForm">
      <label for="nom">Nom :</label>
      <input type="text" id="nom" v-model="nom" required>

      <label for="prenom">Prénom :</label>
      <input type="text" id="prenom" v-model="prenom" required>

      <label for="email">Email :</label>
      <input type="email" id="email" v-model="email" required>

      <label for="telephone">Numéro de Téléphone :</label>
      <input type="tel" id="telephone" v-model="telephone" required>

      <button type="submit">S'Inscrire</button>
    </form>

    <!-- Boîte de dialogue de confirmation -->
    <div class="popup-modal">
      <div class="window">
    <PopupModal  v-if="showConfirmationPopup" ref="confirmationDialog">
      <h1>Bienvenue sur la Liste d'Attente</h1>
    <h2>Inscrits :</h2>
      <p>Êtes-vous sûr de vouloir vous inscrire ?</p>
      <button @click="handleConfirmation">Confirmer</button>
      <button @click="closeConfirmationDialog">Annuler</button>
    </PopupModal>
    
  </div>
</div>


  </div>
</template>

<script>
import axios from 'axios';
import popupmodal from './popupmodal.vue'; // Assurez-vous d'importer correctement le chemin vers votre composant PopupModal

export default {
  data() {
    return {
      nom: "",
      prenom: "",
      email: "",
      telephone: "",
      showConfirmationPopup: false, // Initialisation à false
    };
  },
  methods: {
    submitForm() {
      // Vous pouvez envoyer les données au backend ici
      const userData = {
        nom: this.nom,
        prenom: this.prenom,
        email: this.email,
        telephone: this.telephone,
      };

      

      // Utilisez Axios pour effectuer une requête POST vers le backend
      axios.post('/soumettre-formulaire', userData)
        .then(response => {
          // Gérer la réponse du backend si nécessaire
          console.log("Réponse du backend :", response.data);

             // Afficher la boîte de dialogue de confirmation
        this.showConfirmationPopup = true;

          
        })
        .catch(error => {
          // Gérer les erreurs
          console.error("Erreur lors de la soumission du formulaire :", error);
        });

      // Réinitialisez les champs après la soumission du formulaire
      this.nom = "";
      this.prenom = "";
      this.email = "";
      this.telephone = "";
    },


    handleConfirmation() {
      // Logique pour gérer l'inscription confirmée
      console.log("Inscription confirmée !");
      // Fermez la boîte de dialogue de confirmation
      this.showConfirmationPopup = false;
    },
    closeConfirmationDialog() {
      // Fermez la boîte de dialogue de confirmation sans confirmer
      this.showConfirmationPopup = false;
    },


  },
  components: {
    popupmodal,
  },
};
</script>

<style scoped>
/* Ajoutez du CSS spécifique à ce composant ici */

  /* Classe CSS pour l'animation de transition */
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.3s;
  }
  .fade-enter,
  .fade-leave-to {
    opacity: 0;
  }



/* Ajoutez du CSS spécifique à ce composant ici */
.popup-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
 
}

.window {
  background: #fff;
  border-radius: 5px;
  box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
  max-width: 480px;
  padding: 1rem;
}
</style>
