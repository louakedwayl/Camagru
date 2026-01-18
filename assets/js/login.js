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
    }
    catch (error)
    {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
    
});