import './bootstrap.js';
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


