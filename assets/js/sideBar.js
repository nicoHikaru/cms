let sideBarArrow = document.getElementById('sideBarArrow');
let sideBar = document.querySelector('.sideBar');
let liste = document.querySelector('.liste');
let listeSideBar = document.getElementById('listeSideBar');
let sideBarArrowRight = document.getElementById('sideBarArrowRight');


if(sideBar !== null) {
    sideBarArrow.addEventListener('click', () => {
        sideBar.style.width = "5%";
        sideBar.style.transition = "1s";
        liste.style.display = "none";
        liste.style.transition = "0.5s";
        listeSideBar.style.display = "block";
    });

    sideBarArrowRight.addEventListener('click', () => {
        sideBar.style.width = "15%";
        sideBar.style.transition = "1s";
        liste.style.opacity = 1;
        liste.style.display = "block";
        liste.style.transition = "0.5s";
        listeSideBar.style.display = "none";
    });
}
