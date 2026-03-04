// Mobile
const mobileIcon = document.querySelector("img.explore-icon");
if (mobileIcon) mobileIcon.src = "assets/images/icon/compass_black.svg";

// Desktop
const desktopIcon = document.querySelector("img.icon.explore:not(.nav)");
if (desktopIcon) desktopIcon.src = "assets/images/icon/compass_black.svg";

document.querySelectorAll('.explore-item').forEach(item => {
    item.addEventListener('click', () => {
        const postId = item.dataset.postId;
        window.location.href = 'index.php?action=visitor_post&id=' + postId;
    });
});