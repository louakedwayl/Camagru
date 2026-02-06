const morelink = document.querySelector("a.icon.more");
const moreButton = document.querySelector("img.icon.more");
const menuHamburger = document.querySelector("div.hamburger");
const reportButtonHamburger = document.querySelector("a.report.hamburger");
const modale = document.querySelector("dialog#modale-report");
const crossModale = document.querySelector("a.modale.cross");

// ✅ DIAGNOSTIC - Vérifie que tout est trouvé
console.log("=== DIAGNOSTIC ===");
console.log("1. morelink:", morelink);
console.log("2. moreButton:", moreButton);
console.log("3. menuHamburger:", menuHamburger);
console.log("4. reportButtonHamburger:", reportButtonHamburger);
console.log("5. modale:", modale);
console.log("6. crossModale:", crossModale);
console.log("==================");

let flag = 0;

morelink.addEventListener("click", (e) => 
{
    e.preventDefault(); 
    e.stopPropagation(); 
    if (flag == 0)
    {
        moreButton.setAttribute("src", "assets/images/icon/more_black.svg")
        menuHamburger.style.display = "inline";
        flag = 1;
    }
    else
    {
        flag = 0;
        moreButton.setAttribute("src", "assets/images/icon/more.svg")
        menuHamburger.style.display = "none";
    }
});

document.addEventListener("click", () => 
{
    if (flag == 1)
    {
        flag = 0;
        moreButton.setAttribute("src", "assets/images/icon/more.svg")
        menuHamburger.style.display = "none";
    }
});

reportButtonHamburger.addEventListener("click", (e) =>
{
    e.preventDefault();
    console.log("Bouton Report cliqué !"); // Debug
    modale.showModal();
    document.body.style.overflow = "hidden";
});

crossModale.addEventListener("click", (e) =>
{
    e.preventDefault();
    console.log("Croix cliquée !"); // Debug
    modale.close();
    document.body.style.overflow = "";
});

// Fermer avec Escape (géré automatiquement par <dialog>, mais on garde pour overflow)
modale.addEventListener('cancel', (e) => {
    console.log("Escape pressé !"); // Debug
    document.body.style.overflow = "";
});

// Fermer en cliquant sur le backdrop
modale.addEventListener('click', (e) => {
    if (e.target === modale) {
        console.log("Clic sur backdrop !"); // Debug
        modale.close();
        document.body.style.overflow = "";
    }
});