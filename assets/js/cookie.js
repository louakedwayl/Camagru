const cookieModal = document.getElementById('cookie-modal');

if (cookieModal) {
    cookieModal.showModal();

    document.getElementById('accept-cookies').addEventListener('click', () => {
        document.cookie = "cookie_consent=true; max-age=" + (365 * 24 * 60 * 60) + "; path=/";
        cookieModal.close();
    });

    document.getElementById('decline-cookies').addEventListener('click', () => {
        document.cookie = "cookie_consent=false; max-age=" + (365 * 24 * 60 * 60) + "; path=/";
        cookieModal.close();
    });
}
