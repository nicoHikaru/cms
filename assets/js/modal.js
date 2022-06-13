// Get the modal
let modal = document.getElementById("myModal");
if(modal !== null) {
  // Get the button that opens the modal
let btn = document.getElementById("favoris");
let btnsHome = document.querySelectorAll('.modalCarts');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
if(btn !== null) {
  btn.onclick = function() {
    modal.style.display = "block";
  }
   // When the user clicks on <span> (x), close the modal
   span.onclick = function() {
    modal.style.display = "none";
  
    }
}

if(btnsHome !== null) {
  btnsHome.forEach((btn) => {
    btn.onclick = function() {
      modal.style.display = "block";
    }
  })
  
   // When the user clicks on <span> (x), close the modal
   span.onclick = function() {
    modal.style.display = "none";
  
    }
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
}
