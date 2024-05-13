// on effectue tout cela apres le chargement du DOM 
document.addEventListener('DOMContentLoaded', () => {

  //On se place sur les différents input
  const inputNumero = document.getElementById("form_adresse_number");
  const inputRue = document.getElementById("form_adresse_street");
  const inputVille = document.getElementById("form_adresse_city");
  const inputDepartement = document.getElementById("form_adresse_county");
  const inputPays = document.getElementById("form_adresse_country");
  
  console.log("bonjour");
  
  async function autoCompleteAdress() {
    //On récupére la value des input utile à la recherche
    const numero = inputNumero.value;
    const rue = inputRue.value;
    const ville = inputVille.value;
  
    // On crée la requéte
    const query = `${numero} ${rue} ${ville}`;
    try {
      //Appel de l'API Adresse avec la requéte
      const response = await fetch(
        `https://api-adresse.data.gouv.fr/search/?q=${query}`
      );
      const data = await response.json();
  
      // Selection du conteneur de suggestion
      const suggestionsContainer = document.getElementById("adresse-suggestions");
      // effacez les suggestions précédentes
      suggestionsContainer.innerHTML = "";
  
      if (data.features.length > 0) {
        // Parcour les résultats et créez des éléments pour les suggestions
        data.features.forEach(({properties}) => {
          const suggestion = document.createElement("div");
          suggestion.textContent = properties.label;
          suggestion.addEventListener("click", () => {
            //Mettre a jour la value des champs avec la valeurs sélectionnées
            inputNumero.value = properties.housenumber;
            inputRue.value = properties.street;
            inputVille.value = properties.city;
            // feature.properties.context est sous la forme : "59, Nord, Haut-de-France" on split en un tableau et selectionne l'élément 1 pour avoir accés au nom du departement
            let context = properties.context.split(", ");
            let departement = context[1];
            inputDepartement.value = departement;
            inputPays.value = "France";
            // Effacez la selection apres le click
            suggestionsContainer.innerHTML = "";
          });
          suggestionsContainer.appendChild(suggestion);
        });
      }
    } catch (error) {
      console.error("Erreur lors du fetch:", error);
    }
  }
  //On écoute les champs et on appelle la fonction au chamgement
  inputNumero.addEventListener("input", autoCompleteAdress);
  inputRue.addEventListener("input", autoCompleteAdress);
  inputVille.addEventListener("input", autoCompleteAdress);
});
