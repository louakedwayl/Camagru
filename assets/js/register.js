/* -------------------------------------------
  RÈGLES DE VALIDATION DU FORMULAIRE :
  
  EMAIL :
  - Format email standard (ex: user@example.com)
  - Vérifié avec regex
  
  FULLNAME (Nom complet) :
  - Lettres (a-z, A-Z) avec accents autorisés (À-ÿ)
  - Espaces, tirets (-) et apostrophes (') autorisés
  - Minimum 2 caractères
  - Maximum 50 caractères
  - INTERDIT : chiffres, emojis, caractères spéciaux
  
  PASSWORD (Mot de passe) :
  - Minimum 6 caractères
  - Au moins 1 lettre majuscule obligatoire
  - INTERDIT : emojis
  
  USERNAME (Nom d'utilisateur - style Instagram) :
  - Lettres (a-z, A-Z) avec accents autorisés (À-ÿ)
  - Chiffres (0-9) autorisés
  - Underscore (_) et point (.) autorisés
  - Minimum 3 caractères
  - Maximum 30 caractères
  - INTERDIT : espaces, emojis, autres symboles
  
La vérification côté serveur est faite en envoyant 
l’input de l’utilisateur au backend via le routeur avec fetch.

------------------------------------------------------------------- */

const emailInput = document.querySelector('main input[name="email"]');
const passwordInput = document.querySelector('main input[name="password"]');
const fullnameInput = document.querySelector('main input[name="fullname"]');
const usernameInput = document.querySelector('main input[name="username"]');

const pErrorEmail = document.querySelector(".top p.error.email");
const pErrorPassword = document.querySelector(".top p.error.password");
const pErrorPasswordUpercase = document.querySelector(".top p.error.uppercase");;
const pErrorFullname = document.querySelector(".top p.error.fullname");
const pErrorFullnameSize = document.querySelector(".top p.error.fullname_size");
const pErrorUsernameSize = document.querySelector(".top p.error.username_size");
const pErrorUsername = document.querySelector(".top p.error.username");
const pErrorUsernameInvailable = document.querySelector(".top p.error.username_invailable");

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordRegex = /^(?!.*\p{Emoji}).{6,}$/u;
const passwordRegexUppercase = /[A-Z]/;
const fullnameSizeRegex = /^.{2,50}$/u;
const fullnameRegex = /^[a-zA-ZÀ-ÿ\s'\-]+$/;
const usernameSizeRegex = /^.{3,30}$/;
const usernameRegex = /^[a-zA-ZÀ-ÿ0-9_.]+$/;

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
      pErrorFullnameSize.style.display = "none";
      fullnameInput.style.borderColor = "";
      fullnameInput.style.marginBottom = "10px";
    }
    else if (!fullnameSizeRegex.test(fullnameInput.value))
    {
      pErrorFullname.style.display = "none";
      fullnameInput.style.borderColor = "red";
      fullnameInput.style.marginBottom = "0px";
      pErrorFullnameSize.style.display = "inline";
    }
    else if (!fullnameRegex.test(fullnameInput.value))
    {
      pErrorFullnameSize.style.display = "none";
      fullnameInput.style.borderColor = "red";
      fullnameInput.style.marginBottom = "0px";
      pErrorFullname.style.display = "inline";
    }
    else 
    {
      pErrorFullname.style.display = "none";
      pErrorFullnameSize.style.display = "none";
      fullnameInput.style.borderColor = "";
      fullnameInput.style.marginBottom = "10px";
    }
});

usernameInput.addEventListener("blur", async () =>
{
    if (usernameInput.value === "")
    {
        pErrorUsernameSize.style.display = "none";
        pErrorUsername.style.display = "none";
        pErrorUsernameInvailable.style.display = "none";
        usernameInput.style.borderColor = "";
        usernameInput.style.marginBottom = "10px";
        return;
    }
    if (!usernameSizeRegex.test(usernameInput.value))
    {
        pErrorUsername.style.display = "none";
        pErrorUsernameInvailable.style.display = "none";
        usernameInput.style.borderColor = "red";
        usernameInput.style.marginBottom = "0px";
        pErrorUsernameSize.style.display = "inline";
        return;
    }
    if (!usernameRegex.test(usernameInput.value))
    {
        pErrorUsernameSize.style.display = "none";
        pErrorUsernameInvailable.style.display = "none";
        usernameInput.style.borderColor = "red";
        usernameInput.style.marginBottom = "0px";
        pErrorUsername.style.display = "inline";
        return;
    }
    
    try 
    {
        const response = await fetch('index.php?action=check_username',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username: usernameInput.value })
        });
        
        const data = await response.json();
        
        if (data.available) 
        {
            pErrorUsername.style.display = "none";
            pErrorUsernameSize.style.display = "none";
            pErrorUsernameInvailable.style.display = "none";
            usernameInput.style.borderColor = "";
            usernameInput.style.marginBottom = "10px";
        } 
        else 
        {
            pErrorUsername.style.display = "none";
            pErrorUsernameSize.style.display = "none";
            usernameInput.style.borderColor = "red";
            usernameInput.style.marginBottom = "0px";
            pErrorUsernameInvailable.style.display = "inline";
        }
    } 
    catch (error) 
    {
        console.error('Erreur lors de la vérification:', error);
        pErrorUsername.style.display = "none";
        pErrorUsernameSize.style.display = "none";
        pErrorUsernameInvailable.style.display = "none";
        usernameInput.style.borderColor = "";
        usernameInput.style.marginBottom = "10px";
    }
});