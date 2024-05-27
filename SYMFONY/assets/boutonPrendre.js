document.addEventListener("DOMContentLoaded", () => {
    const prendreButtons = document.querySelectorAll('.prendre-button');
    
    prendreButtons.forEach(button => {
        console.log('Button found:', button);
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du formulaire

            prendreOperation.call(this, event); // Appelle la fonction prendreOperation en conservant le contexte du bouton cliqué
        });
    });
});
function prendreOperation(event) {
    event.preventDefault();

    const form = this.closest('form');
    console.log('Fetching data...');
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        console.log('Response received:', response);
        if (!response.ok) {
            throw new Error('Erreur lors de la prise de l\'opération.');
        }

        this.closest('.custom-block').remove();

        const counter = document.querySelector(".counter");
        const count = parseInt(counter.dataset.count);
        const maxCount = parseInt(counter.dataset.maxCount);

        if (!isNaN(count) && !isNaN(maxCount)) {
            if (count >= maxCount) {
                const message = "Vous avez atteint le nombre maximal d'opérations autorisées.";
                alert(message);
            } else {
                counter.dataset.count = count + 1;
                let text = counter.textContent.replace(count, count + 1);
                counter.textContent = text;
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}
