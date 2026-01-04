const emailInput = document.querySelector('main input[name="email"]');
const pErrorEmail = document.querySelector(".top p.error.email");
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
let currentHeight;


emailInput.addEventListener("blur", ()=>
{
  if (emailInput.value === "")
    return;

  if (!emailRegex.test(emailInput.value))
  {
    emailInput.style.borderColor = "red";
    emailInput.style.marginBottom = "0px";
    pErrorEmail.style.display = "inline";
  } 
  else
  {
    pErrorEmail.style.display = "none";
    emailInput.style.borderColor = "";
    emailInput.style.marginBottom = "10px";
  }
});