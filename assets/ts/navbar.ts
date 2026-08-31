(() => {
    const nav = document.querySelector('nav')!;
    const searchLink = document.querySelector<HTMLAnchorElement>('a#search-link');
    const searchBar = document.querySelector<HTMLElement>('#search-bar')!;
    const searchCross = document.querySelector<HTMLElement>('.search-cross');
    const notificationLink = document.querySelector<HTMLAnchorElement>('a#notification-link');
    const notificationBar = document.querySelector<HTMLElement>('#notification-bar')!;
    const notificationCross = document.querySelector<HTMLElement>('.notification-cross');

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

    const mobileSearchLink = document.getElementById('mobile-search-link');
    if (mobileSearchLink) {
        mobileSearchLink.addEventListener('click', function(e) {
            e.preventDefault();
            searchBar.style.display = "flex";
        });
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth > 900 && searchBar.style.display === "flex") {
            nav.style.display = "none";
        }
        if (window.innerWidth > 900 && searchBar.style.display !== "flex") {
            nav.style.display = "";
        }
    });
})();
