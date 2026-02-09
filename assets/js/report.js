const reportModal = document.getElementById('modale-report');

if (reportModal) {
    const reportForm = reportModal.querySelector('form');
    const reportTextarea = reportForm.querySelector('textarea.report');
    const reportSendBtn = reportForm.querySelector('button.report.send');
    const crossModale = reportModal.querySelector('a.modale.cross');

    // Activer/désactiver le bouton selon le contenu du textarea
    reportTextarea.addEventListener('input', () => {
        if (reportTextarea.value.trim().length > 0) {
            reportSendBtn.disabled = false;
            reportSendBtn.style.backgroundColor = '#5063F9';
            reportSendBtn.style.cursor = 'pointer';
        } else {
            reportSendBtn.disabled = true;
            reportSendBtn.style.backgroundColor = '#B7C6FF';
            reportSendBtn.style.cursor = 'not-allowed';
        }
    });

    // État initial : bouton désactivé
    reportSendBtn.disabled = true;
    reportSendBtn.style.backgroundColor = '#B7C6FF';
    reportSendBtn.style.cursor = 'not-allowed';

    // Fermer avec la croix
    crossModale.addEventListener('click', (e) => {
        e.preventDefault();
        reportModal.close();
        document.body.style.overflow = "";
    });

    // Fermer avec ESC
    reportModal.addEventListener('cancel', () => {
        document.body.style.overflow = "";
    });

    // Fermer en cliquant sur le backdrop
    reportModal.addEventListener('click', (e) => {
        if (e.target === reportModal) {
            reportModal.close();
            document.body.style.overflow = "";
        }
    });

    // Reset du bouton quand la modale se ferme
    reportModal.addEventListener('close', () => {
        reportForm.reset();
        reportSendBtn.disabled = true;
        reportSendBtn.style.backgroundColor = '#B7C6FF';
        reportSendBtn.style.cursor = 'not-allowed';
        reportSendBtn.querySelector('span').textContent = 'Send report';
    });

    // Envoyer le report
    reportForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = reportTextarea.value.trim();
        
        if (!message) {
            return;
        }

        reportSendBtn.disabled = true;
        reportSendBtn.style.backgroundColor = '#B7C6FF';
        reportSendBtn.querySelector('span').textContent = 'Sending...';

        try {
            const response = await fetch('index.php?action=submit_report', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();

            if (data.success) {
                alert('Report sent successfully. Thank you!');
                reportModal.close();
                document.body.style.overflow = "";
            } else {
                alert(data.message || 'Error sending report.');
                reportSendBtn.disabled = false;
                reportSendBtn.style.backgroundColor = '#5063F9';
                reportSendBtn.querySelector('span').textContent = 'Send report';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            reportSendBtn.disabled = false;
            reportSendBtn.style.backgroundColor = '#5063F9';
            reportSendBtn.querySelector('span').textContent = 'Send report';
        }
    });
}