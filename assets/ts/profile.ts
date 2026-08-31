(() => {
    const profileIcon = document.querySelector<HTMLImageElement>("img.icon.profile");
    const btnEditProfile = document.querySelector<HTMLButtonElement>('.btn-edit-profile');
    const modalEditProfile = document.getElementById('modal-edit-profile')!;
    const modalEditClose = document.querySelector<HTMLImageElement>('.modal-edit-close');
    const avatarForm = document.getElementById('avatar-form') as HTMLFormElement | null;
    const avatarInput = document.getElementById('avatar-input') as HTMLInputElement;
    const currentAvatar = document.getElementById('current-avatar') as HTMLImageElement;
    const changePhotoInput = document.getElementById('change-photo-input') as HTMLInputElement | null;
    const editAvatarPreview = document.getElementById('edit-avatar-preview') as HTMLImageElement;
    const btnPublicView = document.querySelector<HTMLButtonElement>('.btn-public-view');
    const shareFirstPhotoLink = document.querySelector<HTMLAnchorElement>("a#create-link");
    const mobileMoreIcon = document.querySelector<HTMLImageElement>(".mobile-more-icon");
    const mobileHamburger = document.querySelector<HTMLElement>(".mobile-hamburger");
    const mobileReportBtn = document.querySelector<HTMLAnchorElement>(".mobile-hamburger a.report.hamburger");

    interface UploadAvatarResponse {
        success: boolean;
        avatar_path: string;
        message?: string;
    }

    function lockScroll() {
        document.documentElement.classList.add('modal-open');
        document.body.classList.add('modal-open');
    }

    function unlockScroll() {
        document.documentElement.classList.remove('modal-open');
        document.body.classList.remove('modal-open');
    }

    document.querySelectorAll<HTMLImageElement>("img.icon.profile, img.profile-icon").forEach(img => {
        img.style.border = "2px solid #262626";
    });

    // OPENING / CLOSING EDIT PROFILE MODAL

    if (btnEditProfile) {
        btnEditProfile.addEventListener('click', (e) => {
            e.preventDefault();
            modalEditProfile.style.display = 'block';
            lockScroll();
        });
    }

    if (modalEditClose) {
        modalEditClose.addEventListener('click', (e) => {
            e.preventDefault();
            modalEditProfile.style.display = 'none';
            unlockScroll();
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' || e.key === 'Esc') {
            if (modalEditProfile.style.display === 'block') {
                modalEditProfile.style.display = 'none';
                unlockScroll();
            }
        }
    });

    // AVATAR UPLOAD FROM MODAL

    if (changePhotoInput) {
        changePhotoInput.addEventListener('change', async (e) => {
            const file = (e.target as HTMLInputElement).files![0];
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
                const data: UploadAvatarResponse = JSON.parse(text);

                if (data.success) {
                    currentAvatar.src = data.avatar_path;
                    editAvatarPreview.src = data.avatar_path;

                    const overlay = document.querySelector<HTMLElement>('.edit-avatar-container .avatar-overlay');
                    const cameraIcon = document.querySelector<HTMLElement>('.edit-avatar-container .camera-white-icon');
                    if (overlay) overlay.style.display = 'none';
                    if (cameraIcon) cameraIcon.style.display = 'none';

                    const profileOverlay = document.querySelector<HTMLElement>('.avatar-label .avatar-overlay');
                    const profileCameraIcon = document.querySelector<HTMLElement>('.avatar-label .camera-white-icon');
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

    // AVATAR UPLOAD FROM PROFILE PAGE

    avatarInput.addEventListener('change', async (e) => {
        const file = (e.target as HTMLInputElement).files![0];
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
            currentAvatar.src = event.target!.result as string;
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
            const data: UploadAvatarResponse = JSON.parse(text);

            if (data.success) {
                currentAvatar.src = data.avatar_path;
                editAvatarPreview.src = data.avatar_path;

                const profileOverlay = document.querySelector<HTMLElement>('.avatar-label .avatar-overlay');
                const profileCameraIcon = document.querySelector<HTMLElement>('.avatar-label .camera-white-icon');
                if (profileOverlay) profileOverlay.style.display = 'none';
                if (profileCameraIcon) profileCameraIcon.style.display = 'none';

                const overlay = document.querySelector<HTMLElement>('.edit-avatar-container .avatar-overlay');
                const cameraIcon = document.querySelector<HTMLElement>('.edit-avatar-container .camera-white-icon');
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

    // PUBLIC VIEW

    if (btnPublicView) {
        btnPublicView.addEventListener('click', function () {
            const username = document.querySelector('.username')!.textContent;
            window.location.href = `?action=user_profile&username=${username}`;
        });
    }

    // GALLERY ITEMS

    const galleryItems = document.querySelectorAll('.gallery-item');

    galleryItems.forEach(item => {
        item.addEventListener('click', () => {
            const postId = item.getAttribute('data-post-id');
            window.location.href = 'index.php?action=post&id=' + postId;
        });
    });

    // SHARE FIRST PHOTO LINK

    if (shareFirstPhotoLink) {
        shareFirstPhotoLink.addEventListener("click", () => {
            window.location.href = 'index.php?action=create';
        });
    }

    // MOBILE HAMBURGER

    if (mobileMoreIcon && mobileHamburger) {
        mobileMoreIcon.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            const isOpen = mobileHamburger.style.display === "block";
            mobileHamburger.style.display = isOpen ? "none" : "block";
            mobileMoreIcon.src = isOpen ? "assets/images/icon/more.svg" : "assets/images/icon/more_black.svg";
        });

        document.addEventListener("click", () => {
            mobileHamburger.style.display = "none";
            mobileMoreIcon.src = "assets/images/icon/more.svg";
        });

        mobileHamburger.addEventListener("click", (e) => {
            e.stopPropagation();
        });
    }

    // MOBILE REPORT BUTTON

    if (mobileReportBtn) {
        mobileReportBtn.addEventListener("click", (e) => {
            e.preventDefault();
            mobileHamburger!.style.display = "none";
            mobileMoreIcon!.src = "assets/images/icon/more.svg";
            const reportModal = document.getElementById('modale-report') as HTMLDialogElement | null;
            if (reportModal) {
                reportModal.showModal();
                lockScroll();
            }
        });
    }

    // RESIZE LISTENER

    window.addEventListener("resize", () => {
        if (window.innerWidth > 900 && mobileHamburger) {
            mobileHamburger.style.display = "none";
            mobileMoreIcon!.src = "assets/images/icon/more.svg";
        }
    });
})();
