// === POST PAGE JS ===

const commentInput = document.querySelector('.comment-input');
const commentSubmit = document.querySelector('.comment-submit');

// Activer/désactiver le bouton Post selon le contenu
if (commentInput && commentSubmit) {
    commentInput.addEventListener('input', () => {
        if (commentInput.value.trim().length > 0) {
            commentSubmit.disabled = false;
        } else {
            commentSubmit.disabled = true;
        }
    });

    // Envoyer le commentaire
    commentSubmit.addEventListener('click', async () => {
        const content = commentInput.value.trim();
        const postId = commentInput.getAttribute('data-post-id');

        if (!content || !postId) return;

        commentSubmit.disabled = true;

        try {
            const response = await fetch('index.php?action=add_comment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ post_id: postId, content: content })
            });

            const data = await response.json();

            if (data.success) {
                // Ajouter le commentaire au DOM
                const commentsSection = document.querySelector('.post-comments-section');
                const newComment = document.createElement('div');
                newComment.classList.add('comment-item');
                newComment.innerHTML = `
                    <img src="${data.avatar || 'assets/images/default-avatar.jpeg'}" alt="Avatar" class="comment-avatar">
                    <div class="comment-content">
                        <a href="index.php?action=user_profile&username=${data.username}" class="comment-username">${data.username}</a>
                        <span class="comment-text">${content}</span>
                        <time class="comment-time">Now</time>
                    </div>
                `;
                commentsSection.appendChild(newComment);
                commentsSection.scrollTop = commentsSection.scrollHeight;

                // Reset input
                commentInput.value = '';
                commentSubmit.disabled = true;
            } else {
                alert(data.message || 'Error adding comment.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });

    // Envoyer avec Enter (sans Shift)
    commentInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (!commentSubmit.disabled) {
                commentSubmit.click();
            }
        }
    });
}

// Focus sur le textarea au clic sur l'icône comment
const iconCommentFocus = document.querySelector('.icon-comment-focus');
if (iconCommentFocus) {
    iconCommentFocus.addEventListener('click', () => {
        commentInput.focus();
    });
}

// Like / Unlike
const iconLike = document.querySelector('.icon-like');
if (iconLike) {
    iconLike.addEventListener('click', async () => {
        const postId = iconLike.getAttribute('data-post-id');
        const isLiked = iconLike.classList.contains('liked');
        const action = isLiked ? 'unlike' : 'like';

        try {
            const response = await fetch(`index.php?action=${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ post_id: postId })
            });

            const data = await response.json();

            if (data.success) {
                iconLike.classList.toggle('liked');
                const likesCount = document.querySelector('.likes-count');
                likesCount.textContent = data.likes_count + ' like' + (data.likes_count > 1 ? 's' : '');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
}