const main_input = document.querySelector("main input");
const main_button = document.querySelector("main button.login-link");
const form = document.querySelector('form');

const snackbar = document.querySelector('.snackbar');
const snackSuccess = document.getElementById('snackbar-success');
const snackFailure = document.getElementById('snackbar-failure');
const snackError = document.getElementById('snackbar-error');

const pErrorTimeout = document.querySelector(".error-timeout");

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const usernameRegex = /^[a-zA-Z0-9._]{3,30}$/;

main_button.disabled = true;

const URL_error = new URLSearchParams(window.location.search).get('error');

if (URL_error)
{
    if (URL_error === "expired")
    {
        pErrorTimeout.style.display = "inline";
    }
}

function isValidInput(value)
{
    return emailRegex.test(value) || usernameRegex.test(value);
}

main_input.addEventListener('click', () => 
{
    pErrorTimeout.style.display = "none";
});


main_input.addEventListener('input', () => 
{
    pErrorTimeout.style.display = "none";
    const value = main_input.value.trim();
    
    if (isValidInput(value)) 
    {
        main_button.style.background = "#5063F9";
        main_button.style.cursor = "pointer";
        main_button.disabled = false;
    }
    else
    {
        main_button.style.background = "#B7C6FF";
        main_button.style.cursor = "not-allowed";
        main_button.disabled = true;
    }
});

function showSnackbar(type) {
    snackSuccess.style.display = 'none';
    snackFailure.style.display = 'none';
    snackError.style.display = 'none';

    if (type === 'success') snackSuccess.style.display = 'block';
    else if (type === 'failure') snackFailure.style.display = 'block';
    else if (type === 'error') snackError.style.display = 'block';

    snackbar.classList.add('show');
    setTimeout(() => {
        snackbar.classList.remove('show');
    }, 4000);
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const value = main_input.value.trim();

    if (!isValidInput(value)) {
        showSnackbar('error');
        return;
    }

    const formData = new FormData(form);

    try {
        main_button.disabled = true;
        const response = await fetch("index.php?action=send_reset_password", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showSnackbar('success');
            main_input.value = "";
            main_button.style.background = "#B7C6FF";
        } else {

            const type = data.reason === 'throttle' ? 'failure' : 'error';
            showSnackbar(type);
            main_button.disabled = false;
        }
    } catch (error) {
        showSnackbar('error');
        main_button.disabled = false;
    }
});