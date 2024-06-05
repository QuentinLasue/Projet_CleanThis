

// import './bootstrap.js';
import './styles/app.css';

// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


// // Connexion

// const rememberMeCheckbox = document.getElementById('rememberMeCo');
// const loginButton = document.getElementById('loginButton');
// const emailInput = document.getElementById('inputEmailCo');
// const passwordInput = document.getElementById('inputPasswordCo');

// const rememberMe = localStorage.getItem('rememberMeCo') === 'true';

// rememberMeCheckbox.checked = rememberMe;

// function updateLoginButtonState() {
//     if (emailInput.value.trim() !== '' && passwordInput.value.trim() !== '') {
//         loginButton.removeAttribute('disabled');
//     } else {
//         loginButton.setAttribute('disabled', 'disabled');
//     }
// }

// emailInput.addEventListener('input', updateLoginButtonState);
// passwordInput.addEventListener('input', updateLoginButtonState);

// loginButton.addEventListener('click', function() {
//     localStorage.setItem('rememberMeCo', rememberMeCheckbox.checked);
// });


// updateLoginButtonState();


// // Ajout de l'écouteur d'événements pour vérifier si les champs d'email et de mot de passe sont vides ou non
// emailInput.addEventListener('input', function() {
//     if (emailInput.value.trim() !== '' && passwordInput.value.trim() !== '') {
//         loginButton.removeAttribute('disabled');
//     } else {
//         loginButton.setAttribute('disabled', 'disabled');
//     }
// });

// passwordInput.addEventListener('input', function() {
//     if (emailInput.value.trim() !== '' && passwordInput.value.trim() !== '') {
//         loginButton.removeAttribute('disabled');
//     } else {
//         loginButton.setAttribute('disabled', 'disabled');
//     }
// });

//Pour la modal tu footer :
// on cible la modal et son icon de fermeture et le contenue de la modal
var modal = document.getElementById("openModal");
var close = document.getElementById("closeModal");
var modalContent = document.getElementById("myModal");

//on ajoute les événement d'apparition et disparition
modal.onclick = function openModal() {
  modalContent.style.display = "block";
};

close.onclick = function closeModal() {
  modalContent.style.display = "none";
};
// ferme la modal quand l'utilisateur clique en dehors de la modal
window.onclick = function (event) {
  if (event.target == modalContent) {
    modalContent.style.display = "none";
  }
};
// // Google login
// document.addEventListener('DOMContentLoaded', function() {
//     const googleButton = document.querySelector('.gsi-material-button');

//     googleButton.addEventListener('click', function() {
//         window.location.href = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=YOUR_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&scope=email%20profile&state=YOUR_STATE";

//     });
// });
    


// //menu burger

// document.addEventListener('DOMContentLoaded', function() {
//     const burgerMenu = document.querySelector('.burger-menu');
//     const navLinks = document.querySelector('.navE-links');
  
//     burgerMenu.addEventListener('click', function() {
//       navLinks.classList.toggle('change');
//     });
//   });
  
