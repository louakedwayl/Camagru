// ============================================
// NOTIFICATION BAR - JS
// ============================================

let notificationsLoaded = false;

/**
 * Toggle the notification bar open/close
 */
function toggleNotificationBar() {
    const bar = document.getElementById('notification-bar');
    const searchBar = document.getElementById('search-bar');

    // Close search bar if open
    if (searchBar && searchBar.classList.contains('active')) {
        searchBar.classList.remove('active');
    }

    bar.classList.toggle('active');

    if (bar.classList.contains('active')) {
        loadNotifications();
        markNotificationsAsRead();
    }
}

/**
 * Close the notification bar
 */
function closeNotificationBar() {
    const bar = document.getElementById('notification-bar');
    bar.classList.remove('active');
}

/**
 * Fetch and render notifications
 */
function loadNotifications() {
    fetch('index.php?action=get_notifications')
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            const list = document.getElementById('notification-list');
            const emptyMsg = document.getElementById('notification-empty');

            if (data.notifications.length === 0) {
                emptyMsg.style.display = 'flex';
                return;
            }

            emptyMsg.style.display = 'none';

            // Clear previous (keep empty msg)
            list.querySelectorAll('.notification-item').forEach(el => el.remove());

            data.notifications.forEach(notif => {
                const item = createNotificationItem(notif);
                list.appendChild(item);
            });

            notificationsLoaded = true;
        })
        .catch(err => console.error('Error loading notifications:', err));
}

/**
 * Create a single notification element
 */
function createNotificationItem(notif) {
    const link = document.createElement('a');
    link.className = 'notification-item' + (notif.is_read == 0 ? ' unread' : '');
    link.href = 'index.php?action=post&id=' + notif.post_id;

    // Avatar
    const avatar = document.createElement('img');
    avatar.className = 'notification-avatar';
    avatar.src = notif.actor_avatar || 'assets/images/default-avatar.jpeg';
    avatar.alt = notif.actor_username;
    link.appendChild(avatar);

    // Text
    const textDiv = document.createElement('div');
    textDiv.className = 'notification-text';

    let html = '<span class="notif-username">' + escapeHtml(notif.actor_username) + '</span> ';

    if (notif.type === 'like') {
        html += '<span class="notif-action">liked your photo.</span> ';
    } else if (notif.type === 'comment') {
        html += '<span class="notif-action">commented: </span>';
        const preview = notif.comment_content 
            ? truncate(notif.comment_content, 40) 
            : '';
        html += '<span class="notif-comment-preview">' + escapeHtml(preview) + '</span> ';
    }

    html += '<span class="notif-time">' + timeAgo(notif.created_at) + '</span>';

    textDiv.innerHTML = html;
    link.appendChild(textDiv);

    // Post thumbnail
    const thumb = document.createElement('img');
    thumb.className = 'notification-post-thumb';
    thumb.src = notif.post_image;
    thumb.alt = 'post';
    link.appendChild(thumb);

    return link;
}

/**
 * Mark all notifications as read + remove badge
 */
function markNotificationsAsRead() {
    fetch('index.php?action=mark_notifications_read', { method: 'POST' })
        .then(res => res.json())
        .then(() => {
            const badge = document.querySelector('.notif-badge');
            if (badge) badge.remove();
        })
        .catch(err => console.error('Error marking notifications:', err));
}

/**
 * Load unread count for the badge (call on page load)
 */
function loadUnreadCount() {
    fetch('index.php?action=get_notifications')
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;
            if (data.unread_count > 0) {
                showBadge(data.unread_count);
            }
        })
        .catch(err => console.error('Error loading unread count:', err));
}

/**
 * Show the red badge on the heart icon
 */
function showBadge(count) {
    const heartBtn = document.getElementById('notif-heart-btn');
    if (!heartBtn) return;

    const existing = heartBtn.querySelector('.notif-badge');
    if (existing) existing.remove();

    if (count > 0) {
        const badge = document.createElement('span');
        badge.className = 'notif-badge';
        heartBtn.appendChild(badge);
    }
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

function escapeHtml(text) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

function truncate(str, maxLen) {
    if (str.length <= maxLen) return str;
    return str.substring(0, maxLen) + '...';
}

function timeAgo(dateString) {
    const now = new Date();
    const date = new Date(dateString);
    const seconds = Math.floor((now - date) / 1000);

    if (seconds < 60) return 'now';
    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return minutes + 'm';
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return hours + 'h';
    const days = Math.floor(hours / 24);
    if (days < 7) return days + 'd';
    const weeks = Math.floor(days / 7);
    if (weeks < 4) return weeks + 'w';
    const months = Math.floor(days / 30);
    return months + 'mo';
}

// ============================================
// INIT: Load unread count on page load
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    loadUnreadCount();
});