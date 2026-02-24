// === HOME VISITOR JS ===

document.querySelectorAll("img.house").forEach(img => {
    img.src = "assets/images/icon/home_black.svg";
});

// "more" link for captions
document.querySelectorAll('.more-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const captionSpan = link.previousElementSibling;
        captionSpan.textContent = captionSpan.dataset.fullText;
        link.style.display = 'none';
    });
});

// === MODALE 3 DOTS ===
const modal = document.getElementById('modal-post-options');
const threeDots = document.querySelectorAll('.three-dots');
let currentPostId = null;

if (modal) {
    threeDots.forEach(dot => {
        dot.addEventListener('click', () => {
            currentPostId = dot.dataset.postId;
            modal.showModal();
            document.body.style.overflow = "hidden";
        });
    });

    // Cancel
    modal.querySelector('.option-cancel').addEventListener('click', () => {
        modal.close();
        document.body.style.overflow = "";
    });

    // Go to post
    modal.querySelector('#go-to-post').addEventListener('click', () => {
        if (currentPostId) {
            window.location.href = 'index.php?action=visitor_post&id=' + currentPostId;
        }
    });

    // Report
    modal.querySelector('.option-report').addEventListener('click', () => {
        modal.close();
        const reportModal = document.getElementById('modale-report');
        if (reportModal) {
            reportModal.showModal();
            document.body.style.overflow = "hidden";
        }
    });

    // Backdrop close
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.close();
            document.body.style.overflow = "";
        }
    });

    modal.addEventListener('cancel', () => {
        document.body.style.overflow = "";
    });
}

// Click on avatar or username -> go to visitor profile
document.querySelectorAll('.post-user-avatar, .post-username').forEach(el => {
    el.style.cursor = 'pointer';
    el.addEventListener('click', () => {
        const username = el.closest('.gallery-top').querySelector('.post-username').textContent;
        window.location.href = 'index.php?action=visitor_profile&username=' + username;
    });
});

const goToProfile = document.getElementById('go-to-profile');
if (goToProfile) {
    goToProfile.addEventListener('click', () => {
        const dot = document.querySelector(`.three-dots[data-post-id="${currentPostId}"]`);
        const username = dot.closest('.gallery-top').querySelector('.post-username').textContent;
        modal.close();
        document.body.style.overflow = "";
        window.location.href = 'index.php?action=visitor_profile&username=' + username;
    });
}