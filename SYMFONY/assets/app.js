
import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

//Connexion 

const rememberMeCheckbox = document.getElementById('rememberMeCo');
const loginButton = document.getElementById('loginButton');
const emailInput = document.getElementById('inputEmailCo');
const passwordInput = document.getElementById('inputPasswordCo');

const rememberMe = localStorage.getItem('rememberMeCo') === 'true';

rememberMeCheckbox.checked = rememberMe;

function updateLoginButtonState() {
    if (emailInput.value.trim() !== '' && passwordInput.value.trim() !== '') {
        loginButton.removeAttribute('disabled');
    } else {
        loginButton.setAttribute('disabled', 'disabled');
    }
}

emailInput.addEventListener('input', updateLoginButtonState);
passwordInput.addEventListener('input', updateLoginButtonState);

loginButton.addEventListener('click', function() {
    localStorage.setItem('rememberMeCo', rememberMeCheckbox.checked);
});

updateLoginButtonState(); 

// Ajout de l'écouteur d'événements pour vérifier si les champs d'email et de mot de passe sont vides ou non
emailInput.addEventListener('input', function() {
    if (emailInput.value.trim() !== '' && passwordInput.value.trim() !== '') {
        loginButton.removeAttribute('disabled');
    } else {
        loginButton.setAttribute('disabled', 'disabled');
    }
});

passwordInput.addEventListener('input', function() {
    if (emailInput.value.trim() !== '' && passwordInput.value.trim() !== '') {
        loginButton.removeAttribute('disabled');
    } else {
        loginButton.setAttribute('disabled', 'disabled');
    }
});


// Google login
document.addEventListener('DOMContentLoaded', function() {
    const googleButton = document.querySelector('.gsi-material-button');

    googleButton.addEventListener('click', function() {
        window.location.href = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=YOUR_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&scope=email%20profile&state=YOUR_STATE";

    });
});
    

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
                throw new Error('Erreur lors de la prise de l\'opération.');
            }
            
           
            this.closest('.custom-block').remove();
        })
        .catch(error => {
            console.error('Erreur:', error);
        });

    });
});
