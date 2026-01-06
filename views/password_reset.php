<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/password_reset.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <script src="assets/js/password_reset.js" defer></script>
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
        <img class="icon padlock" src="assets/images/icon/padlock.svg" alt="padlock icon">
        <p class="padlock1">Trouble logging in?</p>
        <p class="padlock2">Enter your email, or username and we'll</p>
        <p class="padlock3">send you a link to get back into your account.</p>
        <input type="text" placeholder ="Email or Username">
        <button class = "login-link">Send login link</button>
        <a href="#" class = "reset-password">Can't reset your password?</a>
        <div class="or" >
            <div class="left or" ></div>
                <span class="or">OR</span>
                    <div class= "right or" ></div>
        </div>
            <a href="index.php?action=register" class="create-account">Create new account</a>
         <div class="bottom">
            <a href="index.php?action=index" class = "login-back">Back to login</a>
         </div>
    </main>
    <?php require_once "footer.php" ?>
</body>
</html>