// === POST PAGE JS ===

function escapeHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

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
                const commentsSection = document.querySelector('.post-comments-section');
                const newComment = document.createElement('div');
                newComment.classList.add('comment-item');
                newComment.innerHTML = `
                    <img src="${data.avatar || 'assets/images/default-avatar.jpeg'}" alt="Avatar" class="comment-avatar">
                    <div class="comment-content">
                        <a href="index.php?action=user_profile&username=${data.username}" class="comment-username">${data.username}</a>
                        <span class="comment-text">${escapeHTML(content)}</span>
                        <time class="comment-time">Now</time>
                    </div>
                `;
                commentsSection.appendChild(newComment);
                commentsSection.scrollTop = commentsSection.scrollHeight;

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

        try {
            const response = await fetch('index.php?action=toggle_like', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'post_id=' + postId
            });

            const data = await response.json();

            if (data.success) {
                if (data.liked) {
                    iconLike.src = 'assets/images/icon/heart_red.svg';
                    iconLike.classList.add('liked');
                } else {
                    iconLike.src = 'assets/images/icon/heart.svg';
                    iconLike.classList.remove('liked');
                }

                const likesCount = document.querySelector('.likes-count');
                likesCount.textContent = data.likes_count > 0 ? data.likes_count + ' like' + (data.likes_count > 1 ? 's' : '') : '0 likes';
            }
        } catch (error) {
        }
    });
}


// === MODALE 3 DOTS ===
const modal = document.getElementById('modal-post-options');
const threeDots = document.querySelectorAll('.three-dots');

if (modal) {
    threeDots.forEach(dot => {
        dot.addEventListener('click', () => {
            modal.showModal();
            document.body.style.overflow = "hidden";
        });
    });

    // Cancel
    modal.querySelector('.option-cancel').addEventListener('click', () => {
        modal.close();
        document.body.style.overflow = "";
    });

    // Go to profile
    modal.querySelector('#go-to-profile').addEventListener('click', () => {
        const username = document.querySelector('.post-header-desktop .post-username').textContent;
        window.location.href = `index.php?action=user_profile&username=${username}`;
    });

    // Report
    modal.querySelector('.option-report').addEventListener('click', () => {
        modal.close();
        const reportModal = document.getElementById('modale-report');
        if (reportModal) {
            reportModal.showModal();
            document.body.style.overflow = "hidden";
        }
    });

    // Delete post
    const deleteBtn = modal.querySelector('#delete-post');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete this post?')) return;

            const postId = document.querySelector('.three-dots').dataset.postId;

            try {
                const formData = new FormData();
                formData.append('post_id', postId);

                const response = await fetch('index.php?action=delete_post', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = 'index.php?action=home';
                } else {
                    alert(result.error || 'Error deleting post');
                }
            } catch (err) {
                console.error('Delete error:', err);
            }
        });
    }

    // Fermer en cliquant sur le backdrop
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.close();
            document.body.style.overflow = "";
        }
    });

    // Fermer avec ESC
    modal.addEventListener('cancel', () => {
        document.body.style.overflow = "";
    });
}

// Click on avatar or username -> go to profile
document.querySelectorAll('.post-avatar, .post-username, .comment-avatar, .comment-username').forEach(el => {
    el.style.cursor = 'pointer';
    el.addEventListener('click', (e) => {
        e.preventDefault();
        const username = el.closest('.post-header, .comment-item').querySelector('.post-username, .comment-username').textContent;
        window.location.href = 'index.php?action=user_profile&username=' + username;
    });
});