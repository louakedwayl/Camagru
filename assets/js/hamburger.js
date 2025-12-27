const moreButton = document.querySelector("a.icon.more");
const menuHamburger = document.querySelector("div.hamburger");

moreButton.addEventListener("click", (e) => {
  e.stopPropagation(); // empeche la propagation jusqu'a document
  menuHamburger.style.display = "inline";
});

document.addEventListener("click", () => {
  menuHamburger.style.display = "none";
});