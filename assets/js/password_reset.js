const main_input = document.querySelector("main input");
const main_button = document.querySelector("main button.login-link");
const form = document.querySelector('form');

const snackbar = document.querySelector('.snackbar');
const snackSuccess = document.getElementById('snackbar-success');
const snackFailure = document.getElementById('snackbar-failure');

// Regex cohérentes avec ton Validator PHP
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const usernameRegex = /^[a-zA-Z0-9._]{3,30}$/;

function isValidInput(value) {
    return emailRegex.test(value) || usernameRegex.test(value);
}

// Initialisation : bouton désactivé par défaut
main_button.disabled = true;

main_input.addEventListener('input', () => {
    const value = main_input.value.trim();
    
    if (isValidInput(value)) {
        main_button.style.background = "#5063F9";
        main_button.style.cursor = "pointer";
        main_button.disabled = false;
    } else {
        main_button.style.background = "#B7C6FF";
        main_button.style.cursor = "not-allowed";
        main_button.disabled = true;
    }
});

function showSnackbar(isSuccess, message = null) {
    if (isSuccess) {
        snackSuccess.style.display = 'block';
        snackFailure.style.display = 'none';
        if (message) snackSuccess.innerText = message;
    } else {
        snackSuccess.style.display = 'none';
        snackFailure.style.display = 'block';
        if (message) snackFailure.innerText = message;
    }

    snackbar.classList.add('show');
    setTimeout(() => {
        snackbar.classList.remove('show');
    }, 4000);
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const value = main_input.value.trim();

    // Sécurité : on ne lance le fetch que si la Regex est respectée
    if (!isValidInput(value)) {
        showSnackbar(false, "Please enter a valid username or email.");
        return;
    }

    const formData = new FormData(form);

    try {
        // Bloquer le bouton pendant le chargement (éviter les clics multiples)
        main_button.disabled = true;
        main_button.style.opacity = "0.7";

        const response = await fetch("index.php?action=send_reset_password", {
            method: "POST",
            body: formData
        });

        // On vérifie si la réponse est bien du JSON
        if (!response.ok) throw new Error('Server error');
        
        const data = await response.json();

        if (data.success) {
            showSnackbar(true, "A reset link has been sent to your email.");
            main_input.value = "";
            main_button.style.background = "#B7C6FF";
            main_button.disabled = true;
        } else {
            showSnackbar(false, data.message || "We couldn't find an account with that login.");
            main_button.disabled = false;
            main_button.style.opacity = "1";
        }
    }
    catch (error) {
        showSnackbar(false, "An error occurred. Please try again later.");
        main_button.disabled = false;
        main_button.style.opacity = "1";
    }
});