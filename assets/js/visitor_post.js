// === VISITOR POST JS ===

// Like -> redirect to login
document.querySelectorAll('.icon-like').forEach(icon => {
    icon.style.cursor = 'pointer';
    icon.addEventListener('click', () => {
        window.location.href = 'index.php';
    });
});

// Comment icon -> redirect to login
document.querySelectorAll('.icon-comment-focus').forEach(icon => {
    icon.style.cursor = 'pointer';
    icon.addEventListener('click', () => {
        window.location.href = 'index.php';
    });
});

// === MODALE 3 DOTS ===
const modal = document.getElementById('modal-post-options');
const threeDots = document.querySelectorAll('.three-dots');

if (modal) {
    threeDots.forEach(dot => {
        dot.addEventListener('click', () => {
            modal.showModal();
            document.body.style.overflow = "hidden";
        });
    });

    // Cancel
    modal.querySelector('.option-cancel').addEventListener('click', () => {
        modal.close();
        document.body.style.overflow = "";
    });

    // Go to profile
    const goToProfile = modal.querySelector('#go-to-profile');
    if (goToProfile) {
        goToProfile.addEventListener('click', () => {
            const username = document.querySelector('.post-username').textContent;
            window.location.href = 'index.php?action=visitor_profile&username=' + username;
        });
    }

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
document.querySelectorAll('.post-avatar, .post-username, .comment-avatar, .comment-username').forEach(el => {
    el.style.cursor = 'pointer';
    el.addEventListener('click', (e) => {
        e.preventDefault();
        const username = el.closest('.post-header, .comment-item').querySelector('.post-username, .comment-username').textContent;
        window.location.href = 'index.php?action=visitor_profile&username=' + username;
    });
});