function lockScroll() {
    document.documentElement.classList.add('modal-open');
    document.body.classList.add('modal-open');
}

function unlockScroll() {
    document.documentElement.classList.remove('modal-open');
    document.body.classList.remove('modal-open');
}

const reportModal = document.getElementById('modale-report');
if (reportModal) {
    const reportForm = reportModal.querySelector('form');
    const reportTextarea = reportForm.querySelector('textarea.report');
    const reportSendBtn = reportForm.querySelector('button.report.send');
    const crossModale = reportModal.querySelector('a.modale.cross');

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

    reportSendBtn.disabled = true;
    reportSendBtn.style.backgroundColor = '#B7C6FF';
    reportSendBtn.style.cursor = 'not-allowed';

    crossModale.addEventListener('click', (e) => {
        e.preventDefault();
        reportModal.close();
        unlockScroll();
    });

    reportModal.addEventListener('cancel', () => {
        unlockScroll();
    });

    reportModal.addEventListener('click', (e) => {
        if (e.target === reportModal) {
            reportModal.close();
            unlockScroll();
        }
    });

    reportModal.addEventListener('close', () => {
        reportForm.reset();
        reportSendBtn.disabled = true;
        reportSendBtn.style.backgroundColor = '#B7C6FF';
        reportSendBtn.style.cursor = 'not-allowed';
        reportSendBtn.querySelector('span').textContent = 'Send report';
    });

    reportForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = reportTextarea.value.trim();
        if (!message) return;

        reportSendBtn.disabled = true;
        reportSendBtn.style.backgroundColor = '#B7C6FF';
        reportSendBtn.querySelector('span').textContent = 'Sending...';

        try {
            const response = await fetch('index.php?action=submit_report', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message })
            });
            const data = await response.json();

            if (data.success) {
                alert('Report sent successfully. Thank you!');
                reportModal.close();
                unlockScroll();
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