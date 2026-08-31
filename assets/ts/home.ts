(() => {
    document.querySelectorAll<HTMLImageElement>("img.house").forEach(img => {
        img.src = "assets/images/icon/home_black.svg";
    });

    const imgComments = document.querySelectorAll<HTMLImageElement>("img.icon-comment");

    interface ToggleLikeResponse {
        success: boolean;
        likes_count: number;
    }

    const moreLinks = document.querySelectorAll<HTMLAnchorElement>('.more-link');
    moreLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const captionContainer = this.closest('.caption-container')!;
            const caption = captionContainer.querySelector<HTMLElement>('.post-caption')!;
            const fullText = caption.getAttribute('data-full-text');
            caption.textContent = fullText;
            caption.classList.add('expanded');
            this.remove();
        });
    });

    // Modale post options
    let currentPostUsername: string | null = null;
    let currentPostId: string | null = null;
    const threeDotsButtons = document.querySelectorAll<HTMLElement>('.three-dots');
    const modalPostOptions = document.getElementById('modal-post-options') as HTMLDialogElement;
    const optionCancel = document.querySelector<HTMLElement>('.option-cancel')!;
    const optionReport = document.querySelector<HTMLElement>('.option-report')!;
    const optionGoToPost = document.querySelectorAll<HTMLElement>('.option-action')[0];
    const optionGoToProfile = document.querySelectorAll<HTMLElement>('.option-action')[1];


    threeDotsButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const galleryTop = btn.closest('.gallery-top')!;
            currentPostUsername = galleryTop.querySelector('.post-username')!.textContent;
            currentPostId = btn.getAttribute('data-post-id');
            modalPostOptions.showModal();
        });
    });

    optionCancel.addEventListener('click', () => {
        modalPostOptions.close();
    });

    optionReport.addEventListener('click', () => {
        modalPostOptions.close();
        const reportModal = document.getElementById('modale-report') as HTMLDialogElement;
        reportModal.showModal();
        document.body.style.overflow = "hidden";
    });

    optionGoToPost.addEventListener('click', () => {
        modalPostOptions.close();
        window.location.href = 'index.php?action=post&id=' + currentPostId;
    });

    optionGoToProfile.addEventListener('click', () => {
        modalPostOptions.close();
        window.location.href = 'index.php?action=user_profile&username=' + currentPostUsername;
    });


    imgComments.forEach(icon => {
        icon.addEventListener("click", () => {
            const postId = icon.getAttribute('data-post-id');
            window.location.href = 'index.php?action=post&id=' + postId;
        });
    });

    // Click on avatar or username -> go to profile
    document.querySelectorAll<HTMLElement>('.post-user-avatar, .post-username').forEach(el => {
        el.style.cursor = 'pointer';
        el.addEventListener('click', () => {
            const username = el.closest('.gallery-top')!.querySelector('.post-username')!.textContent;
            window.location.href = 'index.php?action=user_profile&username=' + username;
        });
    });

    // Toggle like
    document.querySelectorAll<HTMLImageElement>('.icon-like').forEach(icon => {
      icon.addEventListener('click', async () => {
        const postId = icon.getAttribute('data-post-id');
        const wasLiked = icon.classList.contains('liked');
        const countEl = document.querySelector<HTMLElement>(`.likes-count[data-post-id="${postId}"]`)!;
        const prevCount = countEl.textContent!;

        // Optimistic update
        if (wasLiked) {
          icon.src = 'assets/images/icon/heart.svg';
          icon.classList.remove('liked');
          const n = parseInt(prevCount) - 1;
          countEl.textContent = n > 0 ? String(n) : '';
        } else {
          icon.src = 'assets/images/icon/heart_red.svg';
          icon.classList.add('liked');
          const n = (parseInt(prevCount) || 0) + 1;
          countEl.textContent = String(n);
        }

        try {
          const res = await fetch('index.php?action=toggle_like', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'post_id=' + postId
          });
          const data: ToggleLikeResponse = await res.json();
          if (!data.success) throw new Error('Server rejected');
          // Sync with server truth
          countEl.textContent = data.likes_count > 0 ? String(data.likes_count) : '';
        } catch (err) {
          // Revert on failure
          if (wasLiked) {
            icon.src = 'assets/images/icon/heart_red.svg';
            icon.classList.add('liked');
          } else {
            icon.src = 'assets/images/icon/heart.svg';
            icon.classList.remove('liked');
          }
          countEl.textContent = prevCount;
          console.error('Like error:', err);
        }
      });
    });
})();
