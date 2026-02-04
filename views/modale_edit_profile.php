<div id="modal-edit-profile" class="modal-edit-profile">
    <div class="modal-edit-content">
        <div class="modal-edit-header">
            <h2>Edit Profile</h2>
            <span class="modal-edit-close">&times;</span>
        </div>
        <form id="edit-profile-form">
            <div class="form-group">
                <label for="edit-fullname">Full Name</label>
                <input type="text" id="edit-fullname" name="fullname" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="edit-username">Username</label>
                <input type="text" id="edit-username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="edit-email">Email</label>
                <input type="email" id="edit-email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn-save-profile">Save Changes</button>
        </form>
    </div>
</div>