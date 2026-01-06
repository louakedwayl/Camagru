/* -------------------------------------------
  email: check with regex
  fullname : only letter or space or - or '
         -> forbidden number special caractere emojis
         -> minimun 2 character
         -> max 50 characters
  password : filtrer mot en minuscule
  username : check plus tard avec fetch
*/

const emailInput = document.querySelector('main input[name="email"]');
const pErrorEmail = document.querySelector(".top p.error.email");
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordRegex = /^(?!.*\p{Emoji}).{6,}$/u;

const passwordInput = document.querySelector('main input[name="password"]');
const pErrorPassword = document.querySelector(".top p.error.password");

emailInput.addEventListener("blur", ()=>
{
  if (emailInput.value === "")
  {
    pErrorEmail.style.display = "none";
    emailInput.style.borderColor = "";
    emailInput.style.marginBottom = "10px";
    return;
  }

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


passwordInput.addEventListener("blur", ()=>
{
  if (passwordInput.value === "")
    return;

  if (!passwordRegex.test(passwordInput.value))
  {
    passwordInput.style.borderColor = "red";
    passwordInput.style.marginBottom = "0px";
    pErrorPassword.style.display = "inline";
  } 
  else
  {
    pErrorPassword.style.display = "none";
    passwordInput.style.borderColor = "";
    passwordInput.style.marginBottom = "10px";
  }
});