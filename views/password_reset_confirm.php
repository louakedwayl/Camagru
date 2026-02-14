<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/password_reset_confirm.css">
    <script src="assets/js/password_reset_confirm.js" defer></script>
    <title>Camagru</title>
</head>
<body>
    <header>
        <img src="assets//images/logo.png" alt="Camagru Logo">
        <div class="header-button">
            <a href="index.php?action=index" class="button">Log In</a>
            <a href="index.php?action=register" class="text">Sign Up</a>
        </div>
    </header>
    <main>
        <h1 class="title-reset">Create A Strong Password</h1>
        <p class="description-reset">Your password must be at least 6 characters and should include a Uppercase character.</p>
        <form method="POST">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <input type="hidden" name="code" value="<?= htmlspecialchars($code) ?>">
            <input id="pass1" name="new_password" type="password" placeholder="New password" required>
            <p class="error password">Create a password at least 6 characters long.</p>
            <p class="error uppercase">Password must contain at least one uppercase letter.</p>
            <input id="pass2" name="confirm_password" type="password" placeholder="New password, again" required>
            <button id="submit-btn" class="login-link">Reset Password</button>
        </form>
        <p class="errorMatch">Password doesn't match.</p>
        <p class="errorTimeout">This link has expired (timeout).</p>
    </main>
    <?php require_once "footer.php" ?>
</body>
</html>