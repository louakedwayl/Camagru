<dialog id="modal-edit-profile" class="modal-edit-profile">
    <div class="modal-edit-content">
        <div class="modal-edit-header">
            <h2>Edit Profile</h2>
            <img src="assets/images/icon/cross.svg" alt="Close" class="modal-edit-close">
        </div>
        
        <form id="edit-profile-form">
            <!-- Photo de profil -->
            <div class="profile-photo-section">
            <label for="change-photo-input" class="edit-avatar-container">
                <img src="<?= htmlspecialchars($user['avatar_path'] ?? 'assets/images/default-avatar.jpeg') ?>" 
                    alt="Profile" 
                    class="edit-profile-avatar" 
                    id="edit-avatar-preview">
                <div class="avatar-overlay"></div>
                <?php if (empty($user['avatar_path'])) { ?>
                    <img src="assets/images/icon/camera_white.svg" class="camera-white-icon">
                <?php } ?>
            </label>
            
            <div class="profile-photo-info">
                <span class="profile-username-small"><?= htmlspecialchars($user['username']) ?></span>
                <label for="change-photo-input" class="change-photo-btn">Change profile photo</label>
                <input type="file" id="change-photo-input" accept="image/*" style="display: none;">
            </div>
        </div>

            <!-- Full Name -->
            <div class="form-group-edit">
                <label for="edit-fullname">Full name</label>
                <div class="input-error-container">
                    <input type="text" id="edit-fullname" name="fullname" 
                           value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    <p class="error-edit fullname_size">Full name must be 2-50 characters.</p>
                    <p class="error-edit fullname">Full name must contain only letters, spaces, hyphens, and apostrophes.</p>
                </div>
            </div>

            <!-- Username -->
            <div class="form-group-edit">
                <label for="edit-username">Username</label>
                <div class="input-error-container">
                    <input type="text" id="edit-username" name="username" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    <p class="error-edit username_size">Username must be 3-30 characters.</p>
                    <p class="error-edit username">Username must contain only letters, numbers, underscores, and periods.</p>
                    <p class="error-edit username_unavailable">A user with that username already exists.</p>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group-edit">
                <label for="edit-email">Email</label>
                <div class="input-error-container">
                    <input type="email" id="edit-email" name="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <p class="error-edit email">Enter a valid email address.</p>
                    <p class="error-edit email_unavailable">An account with this email already exists.</p>
                </div>
            </div>

            <!-- New Password -->
            <div class="form-group-edit">
                <label for="edit-password">New Password</label>
                <div class="input-error-container">
                    <input type="password" id="edit-password" name="password" 
                           placeholder="Leave blank to keep current password">
                    <p class="error-edit password">Create a password at least 6 characters long.</p>
                    <p class="error-edit uppercase">Password must contain at least one uppercase letter.</p>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group-edit">
                <label for="edit-password-confirm">Confirm New Password</label>
                <div class="input-error-container">
                    <input type="password" id="edit-password-confirm" name="password_confirm" 
                           placeholder="Confirm new password">
                    <p class="error-edit password_match">Passwords do not match.</p>
                </div>
            </div>

            <!-- Préférence de notification -->
            <div class="form-group-edit notification-group">
                <div class="notification-label-group">
                    <label for="edit-notifications">Email notifications</label>
                    <span class="notification-description">Receive notifications about comments and likes</span>
                </div>
                <label class="switch">
                    <input type="checkbox" id="edit-notifications" name="notifications" 
                           <?php echo isset($user['notifications']) && $user['notifications'] ? 'checked' : ''; ?>>
                    <span class="slider"></span>
                </label>
            </div>

            <button type="submit" class="btn-save-profile">Submit</button>
        </form>
    </div>
</dialog>