/* -------------------------------------------
  VALIDATION DU FORMULAIRE D'ÉDITION DE PROFIL
  
  Mêmes règles que le formulaire de register :
  - EMAIL : format standard
  - FULLNAME : 2-50 caractères, lettres/espaces/tirets/apostrophes
  - PASSWORD : 6+ caractères, 1 majuscule (optionnel ici)
  - USERNAME : 3-30 caractères, lettres/chiffres/underscore/point
  - PASSWORD_CONFIRM : doit correspondre au nouveau mot de passe
------------------------------------------------------------------- */

// Sélecteurs des inputs
const editFullnameInput = document.querySelector('#edit-fullname');
const editUsernameInput = document.querySelector('#edit-username');
const editEmailInput = document.querySelector('#edit-email');
const editPasswordInput = document.querySelector('#edit-password');
const editPasswordConfirmInput = document.querySelector('#edit-password-confirm');
const editForm = document.querySelector('#edit-profile-form');
const submitBtn = document.querySelector('.btn-save-profile');

// Sélecteurs d'erreurs
const pErrorFullnameSize = document.querySelector(".error-edit.fullname_size");
const pErrorFullname = document.querySelector(".error-edit.fullname");
const pErrorUsernameSize = document.querySelector(".error-edit.username_size");
const pErrorUsername = document.querySelector(".error-edit.username");
const pErrorUsernameUnavailable = document.querySelector(".error-edit.username_unavailable");
const pErrorEmail = document.querySelector(".error-edit.email");
const pErrorEmailUnavailable = document.querySelector(".error-edit.email_unavailable");
const pErrorPassword = document.querySelector(".error-edit.password");
const pErrorPasswordUppercase = document.querySelector(".error-edit.uppercase");
const pErrorPasswordMatch = document.querySelector(".error-edit.password_match");

// Regex (identiques au register)
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordRegex = /^(?!.*\p{Extended_Pictographic}).{6,}$/u; 
const passwordRegexUppercase = /[A-Z]/;
const fullnameSizeRegex = /^.{2,50}$/u;
const fullnameRegex = /^[a-zA-ZÀ-ÿ\s'\-]+$/;
const usernameSizeRegex = /^.{3,30}$/;
const usernameRegex = /^[a-zA-ZÀ-ÿ0-9_.]+$/;

// Valeurs initiales (pour vérifier si changement)
const initialUsername = editUsernameInput.value;
const initialEmail = editEmailInput.value;

// Fonction utilitaire pour reset les erreurs
function resetError(input, ...errorElements) {
    input.classList.remove('input-error');
    errorElements.forEach(error => {
        if (error) error.style.display = "none";
    });
}

// Fonction utilitaire pour afficher une erreur
function showError(input, errorElement) {
    input.classList.add('input-error');
    if (errorElement) errorElement.style.display = "block";
}

// Validation du fullname
function validateFullname() {
    if (editFullnameInput.value === "") {
        resetError(editFullnameInput, pErrorFullname, pErrorFullnameSize);
        return false;
    }
    
    if (!fullnameSizeRegex.test(editFullnameInput.value)) {
        resetError(editFullnameInput, pErrorFullname);
        showError(editFullnameInput, pErrorFullnameSize);
        return false;
    }
    
    if (!fullnameRegex.test(editFullnameInput.value)) {
        resetError(editFullnameInput, pErrorFullnameSize);
        showError(editFullnameInput, pErrorFullname);
        return false;
    }
    
    resetError(editFullnameInput, pErrorFullname, pErrorFullnameSize);
    return true;
}

// Validation du username
async function validateUsername() {
    if (editUsernameInput.value === "") {
        resetError(editUsernameInput, pErrorUsernameSize, pErrorUsername, pErrorUsernameUnavailable);
        return false;
    }
    
    if (!usernameSizeRegex.test(editUsernameInput.value)) {
        resetError(editUsernameInput, pErrorUsername, pErrorUsernameUnavailable);
        showError(editUsernameInput, pErrorUsernameSize);
        return false;
    }
    
    if (!usernameRegex.test(editUsernameInput.value)) {
        resetError(editUsernameInput, pErrorUsernameSize, pErrorUsernameUnavailable);
        showError(editUsernameInput, pErrorUsername);
        return false;
    }
    
    // Si le username n'a pas changé, pas besoin de vérifier la disponibilité
    if (editUsernameInput.value === initialUsername) {
        resetError(editUsernameInput, pErrorUsernameSize, pErrorUsername, pErrorUsernameUnavailable);
        return true;
    }
    
    // Vérification de disponibilité côté serveur
    try {
        const formData = new FormData();
        formData.append('username', editUsernameInput.value);
        
        const response = await fetch('index.php?action=check_username', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.available) {
            resetError(editUsernameInput, pErrorUsernameSize, pErrorUsername, pErrorUsernameUnavailable);
            return true;
        } else {
            resetError(editUsernameInput, pErrorUsernameSize, pErrorUsername);
            showError(editUsernameInput, pErrorUsernameUnavailable);
            return false;
        }
    } catch (error) {
        return false;
    }
}

// Validation de l'email
async function validateEmail() {
    if (editEmailInput.value === "") {
        resetError(editEmailInput, pErrorEmail, pErrorEmailUnavailable);
        return false;
    }
    
    if (!emailRegex.test(editEmailInput.value)) {
        resetError(editEmailInput, pErrorEmailUnavailable);
        showError(editEmailInput, pErrorEmail);
        return false;
    }
    
    // Si l'email n'a pas changé, pas besoin de vérifier la disponibilité
    if (editEmailInput.value === initialEmail) {
        resetError(editEmailInput, pErrorEmail, pErrorEmailUnavailable);
        return true;
    }
    
    // Vérification de disponibilité côté serveur
    try {
        const formData = new FormData();
        formData.append('email', editEmailInput.value);
        
        const response = await fetch("index.php?action=check_email", {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.available) {
            resetError(editEmailInput, pErrorEmail, pErrorEmailUnavailable);
            return true;
        } else {
            resetError(editEmailInput, pErrorEmail);
            showError(editEmailInput, pErrorEmailUnavailable);
            return false;
        }
    } catch (error) {
        return false;
    }
}

// Validation du password (optionnel - seulement si rempli)
function validatePassword() {
    // Si vide, c'est OK (on ne change pas le mot de passe)
    if (editPasswordInput.value === "") {
        resetError(editPasswordInput, pErrorPassword, pErrorPasswordUppercase);
        return true;
    }
    
    if (!passwordRegex.test(editPasswordInput.value)) {
        resetError(editPasswordInput, pErrorPasswordUppercase);
        showError(editPasswordInput, pErrorPassword);
        return false;
    }
    
    if (!passwordRegexUppercase.test(editPasswordInput.value)) {
        resetError(editPasswordInput, pErrorPassword);
        showError(editPasswordInput, pErrorPasswordUppercase);
        return false;
    }
    
    resetError(editPasswordInput, pErrorPassword, pErrorPasswordUppercase);
    return true;
}

// Validation de la confirmation du password
function validatePasswordConfirm() {
    // Si le nouveau password est vide, pas besoin de confirmer
    if (editPasswordInput.value === "") {
        resetError(editPasswordConfirmInput, pErrorPasswordMatch);
        return true;
    }
    
    // Si le password de confirmation ne correspond pas
    if (editPasswordConfirmInput.value !== editPasswordInput.value) {
        showError(editPasswordConfirmInput, pErrorPasswordMatch);
        return false;
    }
    
    resetError(editPasswordConfirmInput, pErrorPasswordMatch);
    return true;
}

// Event Listeners "Blur" (validation à la sortie du champ)
editFullnameInput.addEventListener("blur", validateFullname);
editUsernameInput.addEventListener("blur", validateUsername);
editEmailInput.addEventListener("blur", validateEmail);
editPasswordInput.addEventListener("blur", validatePassword);
editPasswordConfirmInput.addEventListener("blur", validatePasswordConfirm);

// Validation aussi quand on tape dans le password confirm
editPasswordConfirmInput.addEventListener("input", validatePasswordConfirm);

// SOUMISSION DU FORMULAIRE
editForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    submitBtn.disabled = true;
    
    // Validation de tous les champs
    const isFullnameValid = validateFullname();
    const isUsernameValid = await validateUsername();
    const isEmailValid = await validateEmail();
    const isPasswordValid = validatePassword();
    const isPasswordConfirmValid = validatePasswordConfirm();
    
    if (!isFullnameValid || !isUsernameValid || !isEmailValid || !isPasswordValid || !isPasswordConfirmValid) {
        submitBtn.disabled = false;
        return;
    }
    
    // Envoi des données au serveur
    try {
        const formData = new FormData();
        formData.append('fullname', editFullnameInput.value);
        formData.append('username', editUsernameInput.value);
        formData.append('email', editEmailInput.value);
        formData.append('password', editPasswordInput.value);
        formData.append('notifications', document.getElementById('edit-notifications').checked ? '1' : '0');
        
        const response = await fetch('index.php?action=update_profile', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Fermer la modale
            document.getElementById('modal-edit-profile').style.display = 'none';
            document.body.style.overflow = '';
            
            // Recharger la page ou afficher un message de succès
            window.location.reload();
        } else {
            // Afficher les erreurs retournées par le serveur
            submitBtn.disabled = false;
            alert(data.message || 'An error occurred. Please try again.');
        }
    } catch (error) {
        submitBtn.disabled = false;
        alert('An error occurred. Please try again.');
    }
});