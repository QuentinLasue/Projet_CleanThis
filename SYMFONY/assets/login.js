
var oubliB = document.getElementById('forgot');
oubliB.addEventListener('click', ()=>{
    var modal = document.getElementById('modalEmailCo');
    modal.classList.remove('modalEmailCo');
    modal.classList.add('modalLogin');
})