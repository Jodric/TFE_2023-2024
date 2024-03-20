import { createApp } from "vue";
import Liste from "./component/liste.vue";
import { createVfm } from 'vue-final-modal';
import 'vue-final-modal/style.css';

const vfm = createVfm();

const app = createApp({});
app.component('liste', Liste);
app.use(vfm).mount('#app');




// gérer l'affichache des horaires
document.querySelectorAll('.date-link').forEach(function(link) {
    link.addEventListener('click', function(event) {
        event.preventDefault(); // Empêche le lien de rediriger
        // Récupère l'identifiant unique de la liste d'horaires correspondante
        var index = this.dataset.index;
        var horaires = document.getElementById('horaires-' + index);
        // Affiche ou masque la liste d'horaires en fonction de son état actuel
        if (horaires) {
            horaires.style.display = (horaires.style.display === 'block') ? 'none' : 'block';
        }
    });
});



  
