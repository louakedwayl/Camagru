(() => {
    document.querySelectorAll<HTMLElement>('.gallery-item').forEach(item => {
        item.addEventListener('click', () => {
            const postId = item.dataset.postId;
            window.location.href = 'index.php?action=post&id=' + postId;
        });
    });
})();
