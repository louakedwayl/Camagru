const iconHome = document.querySelector("img.house");
iconHome.src = "assets/images/icon/home_black.svg";
const imgComments = document.querySelectorAll("img.icon-comment");

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
let currentPostUsername = null;
let currentPostId = null;
const threeDotsButtons = document.querySelectorAll('.three-dots');
const modalPostOptions = document.getElementById('modal-post-options');
const optionCancel = document.querySelector('.option-cancel');
const optionReport = document.querySelector('.option-report');
const optionGoToPost = document.querySelectorAll('.option-action')[0];
const optionGoToProfile = document.querySelectorAll('.option-action')[1];


threeDotsButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const galleryTop = btn.closest('.gallery-top');
        currentPostUsername = galleryTop.querySelector('.post-username').textContent;
        currentPostId = btn.getAttribute('data-post-id');
        modalPostOptions.showModal();
    });
});

optionCancel.addEventListener('click', () => {
    modalPostOptions.close();
});

optionReport.addEventListener('click', () => {
    modalPostOptions.close();
    const reportModal = document.getElementById('modale-report');
    reportModal.showModal();
    document.body.style.overflow = "hidden";
});

optionGoToPost.addEventListener('click', () => {
    modalPostOptions.close();
    window.location.href = 'index.php?action=post&id=' + currentPostId;
});

optionGoToProfile.addEventListener('click', () => {
    modalPostOptions.close();
    window.location.href = 'index.php?action=user_profile&username=' + currentPostUsername;
});


imgComments.forEach(icon => {
    icon.addEventListener("click", () => {
        const postId = icon.getAttribute('data-post-id');
        window.location.href = 'index.php?action=post&id=' + postId;
    });
});