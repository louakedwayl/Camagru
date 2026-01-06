/* -------------------------------------------
  email: check with regex
  fullname : only letter or space or - or ' entre 2 et 50 caractères pas demoji
         -> forbidden number special caractere emojis
         -> minimun 2 character
         -> max 50 characters
  password : filtrer au moin 6 caractere ,mot en minuscule et emojis
  username : 3 à 30 caractères  : Lettres, chiffres, underscore et point (style Instagram)
  lettres (a–z, A–Z)
  chiffres (0–9)
  underscore _
  check plus tard avec fetch

  Pas :
  pas de point
  espaces
  accents
  emojis
  symboles
*/

// coder le username p error


const emailInput = document.querySelector('main input[name="email"]');
const passwordInput = document.querySelector('main input[name="password"]');
const fullnameInput = document.querySelector('main input[name="fullname"]');

const pErrorEmail = document.querySelector(".top p.error.email");
const pErrorPassword = document.querySelector(".top p.error.password");
const pErrorPasswordUpercase = document.querySelector(".top p.error.uppercase");;
const pErrorFullname = document.querySelector(".top p.error.fullname");


const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordRegex = /^(?!.*\p{Emoji}).{6,}$/u;
const passwordRegexUppercase = /[A-Z]/;
const fullnameRegex = /^[\p{L} '-]{2,50}$/u;
const usernameRegex = /^[a-zA-Z0-9_.]{3,30}$/;

emailInput.addEventListener("blur", ()=>
{
  if (emailInput.value === "")
  {
    pErrorEmail.style.display = "none";
    emailInput.style.borderColor = "";
    emailInput.style.marginBottom = "10px";
  }
  else if (!emailRegex.test(emailInput.value))
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
  {
    pErrorPasswordUpercase.style.display = "none";
    pErrorPassword.style.display = "none";
    passwordInput.style.borderColor = "";
    passwordInput.style.marginBottom = "10px";
  }
  else if (!passwordRegex.test(passwordInput.value))
  {
    passwordInput.style.borderColor = "red";
    passwordInput.style.marginBottom = "0px";
    pErrorPassword.style.display = "inline";
  }
  else if (!passwordRegexUppercase.test(passwordInput.value))
  {
    pErrorPassword.style.display = "none";
    passwordInput.style.borderColor = "red";
    passwordInput.style.marginBottom = "0px";
    pErrorPasswordUpercase.style.display = "inline";
  }
  else
  {
    pErrorPasswordUpercase.style.display = "none";
    pErrorPassword.style.display = "none";
    passwordInput.style.borderColor = "";
    passwordInput.style.marginBottom = "10px";
  }
});

fullnameInput.addEventListener("blur", ()=>
{
    if (fullnameInput.value === "")
    {
      pErrorFullname.style.display = "none";
      fullnameInput.style.borderColor = "";
      fullnameInput.style.marginBottom = "10px";
    }
    else if (!fullnameRegex.test(fullnameInput.value))
    {
      fullnameInput.style.borderColor = "red";
      fullnameInput.style.marginBottom = "0px";
      pErrorFullname.style.display = "inline";
    }
    else
    {
      fullnameInput.style.borderColor = "";
      fullnameInput.style.marginBottom = "10px";
      pErrorFullname.style.display = "none";
    }
});