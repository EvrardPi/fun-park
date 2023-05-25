let sidebar = document.querySelector(".sidebar");
let closeBtn = document.querySelector("#btn");
let searchBtn = document.querySelector(".bx-search");

closeBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open"); // la sidebar s'ouvre normalement
    menuBtnChange();
});

searchBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open"); // la sidebar s'ouvre lors du click sur l'icon search
    menuBtnChange();
});

// code pour changer le bouton de la barre latérale
function menuBtnChange() {
    if (sidebar.classList.contains("open")) {
        closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); // replace l'icon class
    } else {
        closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); // replace l'icon class
    }
}