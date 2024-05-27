document.addEventListener("DOMContentLoaded", () => {
    // Sélectionner tous les boutons avec la classe 'prendre-button'
    const prendreButtons = document.querySelectorAll('.prendre-button');
    
    prendreButtons.forEach(button => {
        // Supprimer tous les écouteurs d'événements existants pour éviter les doublons
        button.removeEventListener('click', prendreOperation);
        
        // Ajouter un nouvel écouteur d'événement pour chaque bouton
        button.addEventListener('click', prendreOperation);
    });
});

function prendreOperation(event) {
    // Empêcher le comportement par défaut du bouton (soumission du formulaire)
    event.preventDefault();
    console.log("Click event triggered"); // Message de débogage

    // Récupérer l'URL du formulaire le plus proche
    const formUrl = this.closest('.operation-form').getAttribute('action');

    // Envoyer une requête POST à l'URL du formulaire
    fetch(formUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        // Vérifier si la réponse est correcte
        if (!response.ok) {
            throw new Error('Erreur lors de la prise de l\'opération.');
        }

        // Supprimer le bloc d'opération correspondant
        this.closest('.custom-block').remove();

        // Vérifier si le compteur a déjà été incrémenté
        const counter = document.querySelector(".counter");
        const count = parseInt(counter.dataset.count);
        if (!isNaN(count)) {
            // Incrémenter le compteur seulement si c'est un nombre valide
            counter.dataset.count = count + 1;

            // Mettre à jour le texte du compteur
            let text = counter.textContent;
            const match = text.match(/\d+/);
            if (match) {
                text = text.replace(match[0], count + 1);
                console.log("text =" + text);
            }
            counter.textContent = text;
        }
    })
    .catch(error => {
        // Afficher une erreur en cas de problème avec la requête
        console.error('Erreur:', error);
    });
}
