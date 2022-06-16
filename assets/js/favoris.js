let favoris = document.querySelector('.favoris');
let formFavorisHome = document.querySelectorAll('.formFavorisHome');
let produitId = document.getElementById('produitId');
let userId = document.querySelector('.userId');
// let fav = document.getElementById('fav');

console.log(userId);

if(favoris !== null) {
   favoris.addEventListener('click',(event) => {
      event.preventDefault();
      
      async function getAuteurs(){
         if(userId.value !== "none") {
            let rep = await fetch('/produit/favoris/' + produitId.value + '/' + userId.value , { method: 'GET' });
            let reponse = await rep.json();
   
            if(reponse.favoris === true) {
               let svg = `
                  <svg xmlns="http://www.w3.org/2000/svg" id="fav" class="favColor" width="20" height="20" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                     <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                  </svg>
               `;
               favoris.innerHTML = svg;
            }
            if(reponse.favoris === false) { 
               let svg = `
                  <svg xmlns="http://www.w3.org/2000/svg" id="fav" class="favColor" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                  </svg>
               `;
               favoris.innerHTML = svg ;
            } 
         }
         
     }
     getAuteurs();
   });
}

if(formFavorisHome !== null) {
   formFavorisHome.forEach((favoris) => {

      favoris.addEventListener('click',(event) => {
         event.preventDefault();
         
         async function getFavorisHome(){
            if(userId.value !== "none") {
               let rep = await fetch('/produit/favoris/' + favoris.children[1].value + '/' + userId.value , { method: 'GET' });
               let reponse = await rep.json();
      
               if(reponse.favoris === true) {
                  let svg = `
                     <svg xmlns="http://www.w3.org/2000/svg" class="favorisHome favColor" width="20" height="20" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                     </svg>
                  `;
                  favoris.children[0].innerHTML = svg;
               }
               if(reponse.favoris === false) { 
                  let svg = `
                     <svg xmlns="http://www.w3.org/2000/svg" class="favorisHome favColor" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                           <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                     </svg>
                  `;
                  favoris.children[0].innerHTML = svg ;
               } 
            }
            
        }
        getFavorisHome();
      });
   });
   
}