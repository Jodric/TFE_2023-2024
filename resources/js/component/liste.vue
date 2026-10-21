<template>
  <div>
    <form class="container__connexion" @submit.prevent="submitForm">
      <label class="container__connexion--margB " for="nom">Nom :</label>
      <input class="container__connexion--margB " type="text" id="nom" v-model="nom" required>

      <label class="container__connexion--margB " for="prenom">Prénom :</label>
      <input class="container__connexion--margB " type="text" id="prenom" v-model="prenom" required>

      <label class="container__connexion--margB " for="email">Email :</label>
      <input class="container__connexion--margB " type="email" id="email" v-model="email" required>

      <label class="container__connexion--margB " for="telephone">Numéro de Téléphone :</label>
      <input class="container__connexion--margB " type="tel" id="telephone" v-model="telephone" required>

      <button class="btn__connexion " type="submit">S'Inscrire</button>
    </form>

    <!-- Boîte de dialogue de confirmation -->
    <div class="popup-modal"  v-if="showConfirmationPopup">
      <div class="window">
        <PopupModal  ref="confirmationDialog">
          <h1>Vous faite maintenant parti de la liste d'attente</h1>
          <h2>Inscrit</h2>
          
          <button  @click="handleConfirmation">voir mon profil</button>
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
      // this.showConfirmationPopup = false;
       window.location.href = '/verification';

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
  position: relative;
  top: 0;
  /*left: 0;*/
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
