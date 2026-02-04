const profileIcon = document.querySelector("img.icon.profile");
const btnEditProfile = document.querySelector('.btn-edit-profile');
const modalEditProfile = document.getElementById('modal-edit-profile');
const modalEditClose = document.querySelector('.modal-edit-close');

profileIcon.style.border = "2px solid #262626";

if (btnEditProfile) {
    btnEditProfile.addEventListener('click', (e) => 
    {
        e.preventDefault();
        modalEditProfile.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });
}

// Fermer la modale avec la croix
if (modalEditClose) {
    modalEditClose.addEventListener('click', (e) => {
        e.preventDefault();
        modalEditProfile.style.display = 'none';
        document.body.style.overflow = '';
    });
}

// Fermer la modale avec la touche Échap
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' || e.key === 'Esc') {
        if (modalEditProfile.style.display === 'block') {
            modalEditProfile.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
});


// Preview de la photo de profil dans la modale
const changePhotoInput = document.getElementById('change-photo-input');
const editAvatarPreview = document.getElementById('edit-avatar-preview');

if (changePhotoInput && editAvatarPreview) {
    changePhotoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                editAvatarPreview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}