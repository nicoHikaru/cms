let articles = document.querySelectorAll('.deleteArticle');
let card = document.querySelectorAll('.card');
let box = document.querySelector('.box');

if(articles !== null) {

    articles.forEach((article) => {

        article.addEventListener('click', (event) => {

            event.preventDefault();
            
            let user = article.children[1].value;
            let produit = article.children[2].value;
            let parent = article.parentNode;
            
            async function deleteArticle() {

                let rep = await fetch('/delete/article/' + user + '/' + produit , { method : 'GET' } );
                let response = await rep.json();
                if(response.info === true) {
                    parent.style.display = "none";
                }
               
            }
           
            deleteArticle();
           
        });
    });
   
}

