const editFullnameInput = document.querySelector('#edit-fullname');
const editUsernameInput = document.querySelector('#edit-username');
const editEmailInput = document.querySelector('#edit-email');
const editPasswordInput = document.querySelector('#edit-password');
const editPasswordConfirmInput = document.querySelector('#edit-password-confirm');
const editForm = document.querySelector('#edit-profile-form');
const submitBtn = document.querySelector('.btn-save-profile');

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

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const passwordRegex = /^(?!.*\p{Extended_Pictographic}).{6,}$/u; 
const passwordRegexUppercase = /[A-Z]/;
const fullnameSizeRegex = /^.{2,50}$/u;
const fullnameRegex = /^[a-zA-ZÀ-ÿ\s'\-]+$/;
const usernameSizeRegex = /^.{3,30}$/;
const usernameRegex = /^[a-zA-ZÀ-ÿ0-9_.]+$/;

let initialUsername = editUsernameInput.value;
let initialEmail = editEmailInput.value;

const modal = document.getElementById('modal-edit-profile');
const btnOpenEdit = document.querySelector('.btn-edit-profile');
const btnCloseEdit = document.querySelector('.modal-edit-close');

function resetError(input, ...errorElements) {
    input.classList.remove('input-error');
    errorElements.forEach(error => {
        if (error) error.style.display = "none";
    });
}

function showError(input, errorElement) {
    input.classList.add('input-error');
    if (errorElement) errorElement.style.display = "block";
}

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
    
    if (editUsernameInput.value === initialUsername) {
        resetError(editUsernameInput, pErrorUsernameSize, pErrorUsername, pErrorUsernameUnavailable);
        return true;
    }
    
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
    
    if (editEmailInput.value === initialEmail) {
        resetError(editEmailInput, pErrorEmail, pErrorEmailUnavailable);
        return true;
    }
    
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

function validatePassword() {
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

function validatePasswordConfirm() {
    if (editPasswordInput.value === "") {
        resetError(editPasswordConfirmInput, pErrorPasswordMatch);
        return true;
    }
    
    if (editPasswordConfirmInput.value !== editPasswordInput.value) {
        showError(editPasswordConfirmInput, pErrorPasswordMatch);
        return false;
    }
    
    resetError(editPasswordConfirmInput, pErrorPasswordMatch);
    return true;
}

editFullnameInput.addEventListener("blur", validateFullname);
editUsernameInput.addEventListener("blur", validateUsername);
editEmailInput.addEventListener("blur", validateEmail);
editPasswordInput.addEventListener("blur", validatePassword);
editPasswordConfirmInput.addEventListener("blur", validatePasswordConfirm);

editPasswordConfirmInput.addEventListener("input", validatePasswordConfirm);

editForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    submitBtn.disabled = true;
    
    const isFullnameValid = validateFullname();
    const isUsernameValid = await validateUsername();
    const isEmailValid = await validateEmail();
    const isPasswordValid = validatePassword();
    const isPasswordConfirmValid = validatePasswordConfirm();
    
    if (!isFullnameValid || !isUsernameValid || !isEmailValid || !isPasswordValid || !isPasswordConfirmValid) {
        submitBtn.disabled = false;
        return;
    }
    
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
            modal.close();
            document.body.style.overflow = '';
            window.location.reload();
        } else {
            submitBtn.disabled = false;
            alert(data.message || 'An error occurred. Please try again.');
        }
    } catch (error) {
        submitBtn.disabled = false;
        alert('An error occurred. Please try again.');
    }
});

btnOpenEdit.addEventListener('click', (e) => {
    e.preventDefault();
    
    // Capture les valeurs actuelles à chaque ouverture
    initialUsername = editUsernameInput.value;
    initialEmail = editEmailInput.value;
    
    modal.showModal();
    document.body.style.overflow = 'hidden';
});

btnCloseEdit.addEventListener('click', (e) => {
    e.preventDefault();
    modal.close();
    document.body.style.overflow = '';
});

modal.addEventListener('cancel', (e) => {
    document.body.style.overflow = '';
});


function resetAllErrors() {
    const allInputs = [editFullnameInput, editUsernameInput, editEmailInput, editPasswordInput, editPasswordConfirmInput];
    const allErrors = [
        pErrorFullnameSize, pErrorFullname, 
        pErrorUsernameSize, pErrorUsername, pErrorUsernameUnavailable,
        pErrorEmail, pErrorEmailUnavailable,
        pErrorPassword, pErrorPasswordUppercase, pErrorPasswordMatch
    ];
    
    allInputs.forEach(input => input.classList.remove('input-error'));
    allErrors.forEach(error => {
        if (error) error.style.display = 'none';
    });
}

modal.addEventListener('close', () => {
    editForm.reset();
    resetAllErrors();
    submitBtn.disabled = false;
    document.body.style.overflow = '';
});