const form = document.querySelector("form#login_form");
const pError = document.querySelector("p.error");
const visitorLink = document.querySelector("a.visitor");

form.addEventListener("submit", async (e) =>
{
    e.preventDefault();

    try
    {
        const formData = new FormData(form);

        const response = await fetch ("?action=login", 
        {
            method: "POST",
            body: formData
        });
        
        const data = await response.json();
        if (data.success === false)
        {
            pError.style.display = "inline";
            visitorLink.style.marginBottom = "16px";
        }
        else if (data.success === true)
        {
            window.location.href = data.redirect;
        }
    }
    catch (error)
    {
    }
});


const modal = document.getElementById('cookie-modal');

if (modal) {
    modal.showModal();

    document.getElementById('accept-cookies').addEventListener('click', () => {
        document.cookie = "cookie_consent=true; max-age=" + (365 * 24 * 60 * 60) + "; path=/";
        modal.close();
    });

    document.getElementById('decline-cookies').addEventListener('click', () => {
        document.cookie = "cookie_consent=false; max-age=" + (365 * 24 * 60 * 60) + "; path=/";
        modal.close();
    });
}