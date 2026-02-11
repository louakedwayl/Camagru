// === HOME VISITOR JS ===

// Like -> redirect to login
document.querySelectorAll('.icon-like').forEach(icon => {
    icon.addEventListener('click', () => {
        window.location.href = 'index.php';
    });
});

// Comment -> redirect to login
document.querySelectorAll('.icon-comment').forEach(icon => {
    icon.addEventListener('click', () => {
        window.location.href = 'index.php';
    });
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
            window.location.href = 'index.php?action=post_visitor&id=' + currentPostId;
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