let formProduits = document.querySelectorAll('.formProduits');
let modifSubmit = document.querySelectorAll('.modifSubmit');
let userId = document.getElementById('idUser');

if(formProduits !== null) {
    data(formProduits);
}
function data(formProduits) {
    formProduits.forEach((formProduit) => {
        formProduit.addEventListener('click',(event) => {
            event.preventDefault();

            let p = formProduit.children[0];
            let user = p.children[1].value;
            let produit = p.children[2].value;

            async function modifDataProduit(){
                let rep = await fetch('/produit/edit/' + user + '/' + produit , { method: 'POST' });
                let reponse = await rep.json();

                console.log(reponse.info);
            }
            modifDataProduit();
            
            // console.log(user,produit);
        }); 
    });
}