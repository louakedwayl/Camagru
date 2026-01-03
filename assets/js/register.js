const emailInput = document.querySelector('main input[name="email"]');
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

emailInput.addEventListener("blur", ()=>
{
  if (emailInput.value === "")
    return;

  if (!emailRegex.test(emailInput.value))
  {
    emailInput.style.borderColor = "red";
    emailInput.style.marginBottom = "30px";
  } 
  else
  {
    emailInput.style.borderColor = "";
    emailInput.style.marginBottom = "";
  }
});