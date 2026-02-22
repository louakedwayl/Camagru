const nav = document.querySelector('nav');
const searchLink = document.querySelector('a#search-link');
const searchBar = document.querySelector('#search-bar');
const searchCross = document.querySelector('.search-cross');
const notificationLink = document.querySelector('a#notification-link');
const notificationBar = document.querySelector('#notification-bar');
const notificationCross = document.querySelector('.notification-cross');

if (searchLink) {
    searchLink.addEventListener('click', function(e) {
        e.preventDefault();
        nav.style.display = "none";
        searchBar.style.display = "flex";
    });
}

if (searchCross) {
    searchCross.addEventListener('click', () => {
        searchBar.style.display = "none";
        nav.style.display = "";
    });
}


if (notificationLink) {
    notificationLink.addEventListener('click', function(e) {
        e.preventDefault();
        nav.style.display = "none";
        notificationBar.style.display = "flex";
    });
}

if (notificationCross) {
    notificationCross.addEventListener('click', () => {
        notificationBar.style.display = "none";
        nav.style.display = "";
    });
}