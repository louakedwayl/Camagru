const iconHome = document.querySelector("img.house");
iconHome.src = "assets/images/icon/home_black.svg";

const moreLinks = document.querySelectorAll('.more-link');
moreLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const captionContainer = this.closest('.caption-container');
        const caption = captionContainer.querySelector('.post-caption');
        const fullText = caption.getAttribute('data-full-text');
        caption.textContent = fullText;
        caption.classList.add('expanded');
        this.remove();
    });
});

// Modale post options
const threeDotsButtons = document.querySelectorAll('.three-dots');
const modalPostOptions = document.getElementById('modal-post-options');
const optionCancel = document.querySelector('.option-cancel');
const optionReport = document.querySelector('.option-report');
const optionGoToPost = document.querySelectorAll('.option-action')[0];
const optionGoToProfile = document.querySelectorAll('.option-action')[1];

threeDotsButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        modalPostOptions.showModal();
    });
});

optionCancel.addEventListener('click', () => {
    modalPostOptions.close();
});

optionReport.addEventListener('click', () => {
    modalPostOptions.close();
    // Ouvre ta modale de report ici si besoin
});

optionGoToPost.addEventListener('click', () => {
    modalPostOptions.close();
    // Redirection vers le post
});

optionGoToProfile.addEventListener('click', () => {
    modalPostOptions.close();
    // Redirection vers le profil
});