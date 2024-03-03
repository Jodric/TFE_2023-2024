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
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        nom: "",
        prenom: "",
        email: "",
        telephone: "",
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
    },
  };
  </script>
  
  <style scoped>
  /* Ajoutez du CSS scoped si nécessaire */
  </style>
  