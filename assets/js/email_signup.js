const form = document.querySelector("main form");
const input = document.querySelector("main form input");
const resendLink = document.getElementById("resend-link");
const snackbarPErrorSuccess = document.querySelector("#snackbar-success");
const snackbarPErrorFailure = document.querySelector("#snackbar-failure");

const snackbar = document.querySelector(".snackbar");
const errorContainer = document.querySelector("div.error");
const pErrorInvalid = document.querySelector("p.error");
const pErrorTimeout = document.querySelector("p.error2");


const regex = /^\d{6}$/;
const URL_error = new URLSearchParams(window.location.search).get('error');

let snackbarTimeout = null; 

if (URL_error)
{
    if (URL_error === "timeout")
    {
        pErrorTimeout.style.display = "inline";
    }
    else if (URL_error === "invalid")
    {
        pErrorInvalid.style.display = "inline";
    }
}    



function showSnackbar(type) {

    if (snackbarTimeout) {
        clearTimeout(snackbarTimeout);
    }

    snackbar.style.display = "flex";

    if (type === 'success') 
    {
        snackbarPErrorSuccess.style.display = "block";
        snackbarPErrorFailure.style.display = "none";
    } 
    else
    {
        snackbarPErrorSuccess.style.display = "none";
        snackbarPErrorFailure.style.display = "block";
    }

    snackbarTimeout = setTimeout(() => {
        snackbar.style.display = "none";
        snackbarTimeout = null;
    }, 4000);
}


if (resendLink) 
{
    resendLink.addEventListener("click", async (e) => 
    {
        e.preventDefault();

        resendLink.style.pointerEvents = "none"; 
        try 
        {
            const response = await fetch('index.php?action=resend_code', 
            {
                method: 'POST'
            });
            
            const data = await response.json();

            if (data.success)
            {
                showSnackbar('success');
                
                setTimeout(() => 
                {
                    resendLink.style.pointerEvents = "auto";
                }, 5000);
            }
            else
            {  
                showSnackbar('error');
                setTimeout(() => 
                {
                    resendLink.style.pointerEvents = "auto";
                }, 5000); 
            }

        } 
        catch (error) 
        {
            showSnackbar('error');
            resendLink.style.pointerEvents = "auto";
        }
    });
}

input.addEventListener('input', () => 
{
    pErrorInvalid.style.display = "none";
    pErrorTimeout.style.display = "none";
    snackbar.style.display = "none";
});

form.addEventListener("submit", async (e) => 
{
    e.preventDefault();
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    const codeValue = input.value.trim();
    
    if (!regex.test(codeValue)) 
    {
        pErrorInvalid.style.display = "inline";
        submitBtn.disabled = false;
        return;
    }

    const formData = new FormData(form);

    try {
        const response = await fetch('index.php?action=verify_code',
        {
            method: 'POST',
            body: formData 
        });

        const data = await response.json();

        if (data.success) 
        {
            window.location.href = 'index.php?action=dashboard';
        } 
        else
        {
            submitBtn.disabled = false;
            if (data.error === 'expired')
            {
                pErrorTimeout.style.display = "inline";
            }
            else 
            {
                pErrorInvalid.style.display = "inline";
            }
        }
    }
    catch (error) 
    {
        submitBtn.disabled = false;
        showSnackbar('error');
    }
});