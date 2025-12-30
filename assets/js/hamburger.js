const morelink = document.querySelector("a.icon.more");
const moreButton = document.querySelector("img.icon.more");
const menuHamburger = document.querySelector("div.hamburger");
const reportButtonHamburger = document.querySelector("a.report.hamburger");
const overlay = document.querySelector("div.overlay");
const modale = document.querySelector("div.modale.report");
const crossModale = document.querySelector("a.modale.cross");

let flag = 0;

morelink.addEventListener("click", (e) => 
{
  e.stopPropagation(); // empeche la propagation jusqu'a document
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

reportButtonHamburger.addEventListener("click", ()=>
{
  overlay.style.display = "block";
  modale.style.display = "flex";
});

crossModale.addEventListener( "click" , () =>
{
  overlay.style.display = "none";
  modale.style.display = "none";
});