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
const form = document.querySelector('main form');

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

function validateEmail() 
{
    if (emailInput.value === "")
    {
        pErrorEmail.style.display = "none";
        emailInput.style.borderColor = "";
        emailInput.style.marginBottom = "10px";
        return false;
    }
    if (!emailRegex.test(emailInput.value))
    {
        emailInput.style.borderColor = "red";
        emailInput.style.marginBottom = "0px";
        pErrorEmail.style.display = "inline";
        return false;
    }
    pErrorEmail.style.display = "none";
    emailInput.style.borderColor = "";
    emailInput.style.marginBottom = "10px";
    return true;
}

function validatePassword()
{
    if (passwordInput.value === "")
    {
        pErrorPasswordUpercase.style.display = "none";
        pErrorPassword.style.display = "none";
        passwordInput.style.borderColor = "";
        passwordInput.style.marginBottom = "10px";
        return false;
    }
    if (!passwordRegex.test(passwordInput.value))
    {
        passwordInput.style.borderColor = "red";
        passwordInput.style.marginBottom = "0px";
        pErrorPassword.style.display = "inline";
        return false;
    }
    if (!passwordRegexUppercase.test(passwordInput.value))
    {
        pErrorPassword.style.display = "none";
        passwordInput.style.borderColor = "red";
        passwordInput.style.marginBottom = "0px";
        pErrorPasswordUpercase.style.display = "inline";
        return false;
    }
    pErrorPasswordUpercase.style.display = "none";
    pErrorPassword.style.display = "none";
    passwordInput.style.borderColor = "";
    passwordInput.style.marginBottom = "10px";
    return true;
}

function validateFullname() {
    if (fullnameInput.value === "")
    {
        pErrorFullname.style.display = "none";
        pErrorFullnameSize.style.display = "none";
        fullnameInput.style.borderColor = "";
        fullnameInput.style.marginBottom = "10px";
        return false;
    }
    if (!fullnameSizeRegex.test(fullnameInput.value))
    {
        pErrorFullname.style.display = "none";
        fullnameInput.style.borderColor = "red";
        fullnameInput.style.marginBottom = "0px";
        pErrorFullnameSize.style.display = "inline";
        return false;
    }
    if (!fullnameRegex.test(fullnameInput.value))
    {
        pErrorFullnameSize.style.display = "none";
        fullnameInput.style.borderColor = "red";
        fullnameInput.style.marginBottom = "0px";
        pErrorFullname.style.display = "inline";
        return false;
    }
    pErrorFullname.style.display = "none";
    pErrorFullnameSize.style.display = "none";
    fullnameInput.style.borderColor = "";
    fullnameInput.style.marginBottom = "10px";
    return true;
}

async function validateUsername()
{
    if (usernameInput.value === "")
    {
        pErrorUsernameSize.style.display = "none";
        pErrorUsername.style.display = "none";
        pErrorUsernameInvailable.style.display = "none";
        usernameInput.style.borderColor = "";
        usernameInput.style.marginBottom = "10px";
        return false;
    }
    if (!usernameSizeRegex.test(usernameInput.value))
    {
        pErrorUsername.style.display = "none";
        pErrorUsernameInvailable.style.display = "none";
        usernameInput.style.borderColor = "red";
        usernameInput.style.marginBottom = "0px";
        pErrorUsernameSize.style.display = "inline";
        return false;
    }
    if (!usernameRegex.test(usernameInput.value))
    {
        pErrorUsernameSize.style.display = "none";
        pErrorUsernameInvailable.style.display = "none";
        usernameInput.style.borderColor = "red";
        usernameInput.style.marginBottom = "0px";
        pErrorUsername.style.display = "inline";
        return false;
    }
    
    try
    {
        const response = await fetch('index.php?action=check_username',
          {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
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
            return true;
        }
        else
        {
            pErrorUsername.style.display = "none";
            pErrorUsernameSize.style.display = "none";
            usernameInput.style.borderColor = "red";
            usernameInput.style.marginBottom = "0px";
            pErrorUsernameInvailable.style.display = "inline";
            return false;
        }
    }
    catch (error)
    {
        console.error('Erreur lors de la vérification:', error);
        return false;
    }
}

emailInput.addEventListener("blur", validateEmail);
passwordInput.addEventListener("blur", validatePassword);
fullnameInput.addEventListener("blur", validateFullname);
usernameInput.addEventListener("blur", validateUsername);

form.addEventListener('submit', async (e) =>
{
    e.preventDefault();
    
    // Appeler toutes les fonctions de validation
    const isEmailValid = validateEmail();
    const isPasswordValid = validatePassword();
    const isFullnameValid = validateFullname();
    const isUsernameValid = await validateUsername();
    
    if (!isEmailValid || !isPasswordValid || !isFullnameValid || !isUsernameValid)
    {
        console.error("Formulaire invalide :");
        if (!isEmailValid) console.error("- Email invalide");
        if (!isPasswordValid) console.error("- Mot de passe invalide");
        if (!isFullnameValid) console.error("- Nom complet invalide");
        if (!isUsernameValid) console.error("- Username invalide");
        return;
    }

    // Si tout est OK, envoyer au backend
    try
    {
        const formData = new FormData();
        formData.append('email', emailInput.value);
        formData.append('password', passwordInput.value);
        formData.append('fullname', fullnameInput.value);
        formData.append('username', usernameInput.value);
        
        const response = await fetch('?action=validate_form', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        z
        if (data.valid)
        {
            window.location.replace("index.php?action=email_signup");        
        } 
        else 
        {
            console.log("Formulaire invalide côté serveur");
        }
    } 
    catch (error)
    {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});