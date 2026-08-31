(() => {
    const morelink = document.querySelector<HTMLAnchorElement>("a.icon.more");
    const moreButton = document.querySelector<HTMLImageElement>("img.icon.more");
    const menuHamburger = document.querySelector<HTMLDivElement>("div.hamburger");
    const reportButtonHamburger = document.querySelector<HTMLAnchorElement>("a.report.hamburger");
    const modale = document.querySelector<HTMLDialogElement>("dialog#modale-report");
    let flag = 0;

    function lockScroll() {
        document.documentElement.classList.add('modal-open');
        document.body.classList.add('modal-open');
    }

    if (morelink) {
        morelink.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (flag == 0) {
                moreButton!.setAttribute("src", "assets/images/icon/more_black.svg");
                menuHamburger!.style.display = "inline";
                flag = 1;
            } else {
                flag = 0;
                moreButton!.setAttribute("src", "assets/images/icon/more.svg");
                menuHamburger!.style.display = "none";
            }
        });
    }

    document.addEventListener("click", () => {
        if (flag == 1) {
            flag = 0;
            if (moreButton) moreButton.setAttribute("src", "assets/images/icon/more.svg");
            if (menuHamburger) menuHamburger.style.display = "none";
        }
    });

    if (reportButtonHamburger) {
        reportButtonHamburger.addEventListener("click", (e) => {
            e.preventDefault();
            modale!.showModal();
            lockScroll();
        });
    }
})();
