document.querySelectorAll("img.icon.explore, img.explore-icon").forEach(img => {
    img.src = "assets/images/icon/compass_black.svg";
});

const exploreItems = document.querySelectorAll('.explore-item');

exploreItems.forEach(item => {
    item.addEventListener('click', () => {
        const postId = item.getAttribute('data-post-id');
        window.location.href = 'index.php?action=post&id=' + postId;
    });
});