document.addEventListener('DOMContentLoaded', () => {
    const modalMap = {
        'learn-more': document.getElementById('modal-learn-more'),
        'terms': document.getElementById('modal-terms'),
        'privacy': document.getElementById('modal-privacy'),
        'cookies': document.getElementById('modal-cookies')
    };

    // Open modals from links
    const firstP = document.querySelector('p.first');
    const secondP = document.querySelector('p.second');

    if (firstP) {
        const learnMoreLink = firstP.querySelector('a');
        if (learnMoreLink) {
            learnMoreLink.addEventListener('click', (e) => {
                e.preventDefault();
                modalMap['learn-more']?.showModal();
            });
        }
    }

    if (secondP) {
        const links = secondP.querySelectorAll('a');
        links.forEach(link => {
            const text = link.textContent.trim().toLowerCase();
            link.addEventListener('click', (e) => {
                e.preventDefault();
                if (text === 'terms') modalMap['terms']?.showModal();
                else if (text === 'privacy policy') modalMap['privacy']?.showModal();
                else if (text === 'cookies policy') modalMap['cookies']?.showModal();
            });
        });
    }

    // Close handlers for all legal modals
    Object.values(modalMap).forEach(modal => {
        if (!modal) return;

        const closeBtn = modal.querySelector('.modal-legal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                modal.close();
            });
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.close();
        });
    });
});