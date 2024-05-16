document.addEventListener("DOMContentLoaded", () => {
const prendreButtons = document.querySelectorAll('.prendre-button');


prendreButtons.forEach(button => {
    button.addEventListener('click', function(event) {

        event.preventDefault();


        const formUrl = this.closest('.operation-form').getAttribute('action');


        fetch(formUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => {

            if (!response.ok) {
                throw new Error('Erreur lors de la prise de l\'opération.')
            }


            this.closest('.custom-block').remove();
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });
});
})