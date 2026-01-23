const main_input = document.querySelector("main input");
const main_button = document.querySelector("main button.login-link");
const form = document.querySelector('form');

const snackbar = document.querySelector('.snackbar');
const snackSuccess = document.getElementById('snackbar-success');
const snackFailure = document.getElementById('snackbar-failure');


const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const usernameRegex = /^[a-zA-Z0-9._]{3,30}$/;

main_button.disabled = true;

function isValidInput(value)
{
    return emailRegex.test(value) || usernameRegex.test(value);
}

main_input.addEventListener('input', () => 
{
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

function showSnackbar(isSuccess) 
{
    if (isSuccess)
    {
        snackSuccess.style.display = 'block';
        snackFailure.style.display = 'none';
    }
    else
    {
        snackSuccess.style.display = 'none';
        snackFailure.style.display = 'block';
    }

    snackbar.classList.add('show');
    setTimeout(() => 
    {
        snackbar.classList.remove('show');
    }, 4000);
}

form.addEventListener('submit', async (e) =>
{
    e.preventDefault();

    const value = main_input.value.trim();

    if (!isValidInput(value))
    {
        showSnackbar(false);
        return;
    }

    const formData = new FormData(form);

    try 
    {
        main_button.disabled = true;
        main_button.style.opacity = "0.7";

        const response = await fetch("index.php?action=send_reset_password",
        {
            method: "POST",
            body: formData
        });

        if (!response.ok) throw new Error('Server error');
        
        const data = await response.json();

        if (data.success)
        {
            showSnackbar(true);
            main_input.value = "";
            main_button.style.background = "#B7C6FF";
            main_button.disabled = true;
        }
        else
        {
            showSnackbar(false);
            main_button.disabled = false;
        }
    }
    catch (error) 
    {
        showSnackbar(false);
        main_button.disabled = false;
    }
});