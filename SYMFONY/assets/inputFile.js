function displayFileName(input) {
    //On regarde si un fichier est trouvée sinon on affiche aucun fichier sélectionné 
    const fileName = input.files[0]?.name || "Aucun fichier sélectionné";
    //On remplis l'élément cibler avec le nom du fichier ou notre message par défault
    document.getElementById("file-name").textContent = fileName;
}
// a l'écote du chargement de la page pour lancer la function
document.addEventListener("DOMContentLoaded",function() {
    //écoute le click sur cette élément (balise p)
    document.getElementById("file-name").addEventListener("click", function() {
        // pour lancer le click sur cette autre élémént (input type file)
        document.getElementById("fileInput").click();
    });
}
);