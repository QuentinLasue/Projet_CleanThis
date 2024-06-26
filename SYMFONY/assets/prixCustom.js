document.addEventListener("DOMContentLoaded", () => {
    const typeOperationSelect = document.getElementById('operation_form_type');
    const prixDiv = document.getElementById('inputPrix');
    const prixInput = document.getElementById('operation_form_prix');

    if (typeOperationSelect.value === '4') {
        prixDiv.classList.add('displayBlock'); // Affiche le champ "prix"
        prixDiv.classList.remove('displayNone');
        prixInput.disabled =false;
    }  
      typeOperationSelect.addEventListener('change', function() {
        if (typeOperationSelect.value === '4') {
            prixDiv.classList.add('displayBlock'); // Affiche le champ "prix"
            prixDiv.classList.remove('displayNone');
            prixInput.disabled =false;
        } else {
            prixDiv.classList.add('displayNone'); // Masque le champ "prix"
            prixDiv.classList.remove('displayBlock');
            prixInput.disabled =true;
        }
    });
})
