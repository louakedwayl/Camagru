const nav = document.querySelector('nav');
const searchLink = document.querySelector('a#search-link');
const searchBar = document.querySelector('#search-bar');
const searchCross = document.querySelector('.search-cross');

// Empêcher le comportement par défaut du lien Search
searchLink.addEventListener('click', function(e) {
    e.preventDefault();
    nav.style.display = "none";
    searchBar.style.display = "flex";
});


searchCross.addEventListener('click', ()=>
{
    searchBar.style.display = "none";
    nav.style.display = "flex";
});
