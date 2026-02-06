const profileIcon = document.querySelector("img.icon.profile");
const btnEditProfile = document.querySelector('.btn-edit-profile');
const modalEditProfile = document.getElementById('modal-edit-profile');
const modalEditClose = document.querySelector('.modal-edit-close');

const avatarForm = document.getElementById('avatar-form');
const avatarInput = document.getElementById('avatar-input');
const currentAvatar = document.getElementById('current-avatar');


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


avatarInput.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    
    if (!file) return;
    
    if (!file.type.startsWith('image/')) 
    {
        alert('Please select a valid image file.');
        avatarInput.value = '';
        return;
    }
    
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) 
    {
        alert('Image size must be less than 5MB.');
        avatarInput.value = '';
        return;
    }
    
    const oldAvatarSrc = currentAvatar.src;
    
    const reader = new FileReader();
    reader.onload = (event) => {
        currentAvatar.src = event.target.result;
    };
    reader.readAsDataURL(file);
    
    const formData = new FormData();
    formData.append('avatar', file);
    
   try {
    const response = await fetch('index.php?action=upload_avatar', {
        method: 'POST',
        body: formData
    });
    
    // AJOUTE ÇA POUR VOIR LA RÉPONSE BRUTE
    const text = await response.text();
    console.log('RAW RESPONSE:', text);
    
    const data = JSON.parse(text);
    
    if (data.success) {
        console.log('Avatar uploaded successfully!');
        window.location.reload();
    } else {
        alert(data.message || 'Failed to upload avatar.');
        currentAvatar.src = oldAvatarSrc;
        avatarInput.value = '';
    }
} catch (error) {
    console.error('Upload error:', error);
    alert('An error occurred. Please try again.');
    currentAvatar.src = oldAvatarSrc;
    avatarInput.value = '';
}
});