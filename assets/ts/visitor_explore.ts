(() => {
    // Mobile
    const mobileIcon = document.querySelector<HTMLImageElement>("img.explore-icon");
    if (mobileIcon) mobileIcon.src = "assets/images/icon/compass_black.svg";

    // Desktop
    const desktopIcon = document.querySelector<HTMLImageElement>("img.icon.explore:not(.nav)");
    if (desktopIcon) desktopIcon.src = "assets/images/icon/compass_black.svg";

    document.querySelectorAll<HTMLElement>('.explore-item').forEach(item => {
        item.addEventListener('click', () => {
            const postId = item.dataset.postId;
            window.location.href = 'index.php?action=visitor_post&id=' + postId;
        });
    });
})();
