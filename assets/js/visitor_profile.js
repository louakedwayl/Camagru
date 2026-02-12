document.querySelectorAll('.gallery-item').forEach(item => {
    item.addEventListener('click', () => {
        const postId = item.dataset.postId;
        window.location.href = 'index.php?action=visitor_post&id=' + postId;
    });
});