const profileIcon = document.querySelector("img.icon.profile");
const btnEditProfile = document.querySelector('.btn-edit-profile');
const modalEditProfile = document.getElementById('modal-edit-profile');
const modalEditClose = document.querySelector('.modal-edit-close');
const avatarForm = document.getElementById('avatar-form');
const avatarInput = document.getElementById('avatar-input');
const currentAvatar = document.getElementById('current-avatar');
const changePhotoInput = document.getElementById('change-photo-input');
const editAvatarPreview = document.getElementById('edit-avatar-preview');
const btnPublicView = document.querySelector('.btn-public-view');

document.querySelectorAll("img.icon.profile, img.profile-icon").forEach(img => {
    img.style.border = "2px solid #262626";
});

// OPENING / CLOSING MODAL

if (btnEditProfile) {
    btnEditProfile.addEventListener('click', (e) => {
        e.preventDefault();
        modalEditProfile.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });
}

if (modalEditClose) {
    modalEditClose.addEventListener('click', (e) => {
        e.preventDefault();
        modalEditProfile.style.display = 'none';
        document.body.style.overflow = '';
    });
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' || e.key === 'Esc') {
        if (modalEditProfile.style.display === 'block') {
            modalEditProfile.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
});

// Direct upload from the modal
if (changePhotoInput) {
    changePhotoInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            changePhotoInput.value = '';
            return;
        }

        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Image size must be less than 5MB.');
            changePhotoInput.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('avatar', file);

        try {
            const response = await fetch('index.php?action=upload_avatar', {
                method: 'POST',
                body: formData
            });

            const text = await response.text();
            const data = JSON.parse(text);

            if (data.success) {
                currentAvatar.src = data.avatar_path;
                editAvatarPreview.src = data.avatar_path;
                
                const overlay = document.querySelector('.edit-avatar-container .avatar-overlay');
                const cameraIcon = document.querySelector('.edit-avatar-container .camera-white-icon');
                if (overlay) overlay.style.display = 'none';
                if (cameraIcon) cameraIcon.style.display = 'none';
                
                const profileOverlay = document.querySelector('.avatar-label .avatar-overlay');
                const profileCameraIcon = document.querySelector('.avatar-label .camera-white-icon');
                if (profileOverlay) profileOverlay.style.display = 'none';
                if (profileCameraIcon) profileCameraIcon.style.display = 'none';
            } else {
                alert(data.message || 'Failed to upload avatar.');
                changePhotoInput.value = '';
            }
        } catch (error) {
            alert('An error occurred. Please try again.');
            changePhotoInput.value = '';
        }
    });
}

// Upload direct depuis la page profile
avatarInput.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('Please select a valid image file.');
        avatarInput.value = '';
        return;
    }

    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
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

        const text = await response.text();
        const data = JSON.parse(text);

        if (data.success) {
            currentAvatar.src = data.avatar_path;
            editAvatarPreview.src = data.avatar_path;
            
            const profileOverlay = document.querySelector('.avatar-label .avatar-overlay');
            const profileCameraIcon = document.querySelector('.avatar-label .camera-white-icon');
            if (profileOverlay) profileOverlay.style.display = 'none';
            if (profileCameraIcon) profileCameraIcon.style.display = 'none';
            
            const overlay = document.querySelector('.edit-avatar-container .avatar-overlay');
            const cameraIcon = document.querySelector('.edit-avatar-container .camera-white-icon');
            if (overlay) overlay.style.display = 'none';
            if (cameraIcon) cameraIcon.style.display = 'none';
        } else {
            alert(data.message || 'Failed to upload avatar.');
            currentAvatar.src = oldAvatarSrc;
            avatarInput.value = '';
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
        currentAvatar.src = oldAvatarSrc;
        avatarInput.value = '';
    }
});


if (btnPublicView) {
    btnPublicView.addEventListener('click', function() {
        const username = document.querySelector('.username').textContent;
        window.location.href = `?action=user_profile&username=${username}`;
    });
}

const galleryItems = document.querySelectorAll('.gallery-item');

galleryItems.forEach(item => {
    item.addEventListener('click', () => {
        const postId = item.getAttribute('data-post-id');
        window.location.href = 'index.php?action=post&id=' + postId;
    });
});