const morelink = document.querySelector("a.icon.more");
const moreButton = document.querySelector("img.icon.more");
const menuHamburger = document.querySelector("div.hamburger");
const reportButtonHamburger = document.querySelector("a.report.hamburger");
const overlay = document.querySelector("div.overlay");
const modale = document.querySelector("dialog#modale-report");
const crossModale = document.querySelector("a.modale.cross");

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

reportButtonHamburger.addEventListener("click", (e)=>
{
  e.preventDefault(); 
  overlay.style.display = "block";
  modale.style.display = "flex";
  document.body.style.overflow = "hidden";
});

crossModale.addEventListener( "click" , (e) =>
{
  e.preventDefault(); 
  overlay.style.display = "none";
  modale.style.display = "none";
  document.body.style.overflow = "";
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' || e.key === 'Esc') {
        if (modale && modale.style.display === 'flex') {
            overlay.style.display = "none";
            modale.style.display = "none";
            document.body.style.overflow = "";
        }
    }
});