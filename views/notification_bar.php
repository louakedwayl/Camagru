<link rel="stylesheet" href="assets/css/notification_bar.css">

<!-- NOTIFICATION BAR -->
<div id="notification-bar">
    <div class="notification-header">
        <img class="notification-cross" src="assets/images/icon/search-cross.svg" alt="close" onclick="closeNotificationBar()">
        <h2>Notifications</h2>
    </div>

    <div class="notification-list" id="notification-list">
        <!-- Filled dynamically by JS -->
        <div class="notification-empty" id="notification-empty">
            <p>No notifications yet.</p>
        </div>
    </div>
</div>

<script src="assets/js/notification_bar.js"></script>