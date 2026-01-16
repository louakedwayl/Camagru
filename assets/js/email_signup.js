const form = document.querySelector("main form");
const input = document.querySelector("main form input");
const snackbar = document.querySelector(".snackbar");
const errorContainer = document.querySelector("div.error");
const pErrorInvalid = document.querySelector("p.error");
const pErrorTimeout = document.querySelector("p.error2");

const regex = /^\d{6}$/;



input.addEventListener('input', () => 
{
    pErrorInvalid.style.display = "none";
    pErrorTimeout.style.display = "none";
    snackbar.style.display = "none";
});



form.addEventListener("submit", async (e) => 
{
    e.preventDefault();

    const codeValue = input.value.trim();
    
    if (!regex.test(codeValue)) 
    {
        pErrorInvalid.style.display = "inline";
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
            if (data.error === 'expired_code')
            {
                pErrorTimeout.style.display = "inline";
            }
            else 
            {
                pErrorInvalid.style.display = "inline";
                pErrorInvalid.innerText = "That code isn't valid. You can request a new one.";
            }
        }
    }
    catch (error) 
    {
        console.error("Erreur technique :", error);
    }
});