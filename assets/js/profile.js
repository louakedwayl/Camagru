const profileIcon = document.querySelector("img.icon.profile");

profileIcon.style.border = "2px solid #262626";



// Modale Edit Profile
const btnEditProfile = document.querySelector('.btn-edit-profile');
const modalEditProfile = document.getElementById('modal-edit-profile');
const modalEditClose = document.querySelector('.modal-edit-close');

// Ouvrir la modale
if (btnEditProfile) {
    btnEditProfile.addEventListener('click', () => {
        modalEditProfile.style.display = 'block';
    });
}

// Fermer la modale avec la croix
if (modalEditClose) {
    modalEditClose.addEventListener('click', () => {
        modalEditProfile.style.display = 'none';
    });
}

// Fermer la modale en cliquant à l'extérieur
window.addEventListener('click', (e) => {
    if (e.target === modalEditProfile) {
        modalEditProfile.style.display = 'none';
    }
});