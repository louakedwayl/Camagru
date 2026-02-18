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
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/modale_cookie.css">
    <title>Camagru</title>
    <script src="assets/js/login.js" defer></script>
</head>
<body>
    <main>
        <div class="index_left">
            <img class="landing img" src="assets/images/landing-2x.png" alt="Landing page image">
        </div>
        <div class="index_right">
            <div class="top">
                <img src="assets/images/logo.png" alt="Camagru Logo">
                <form id="login_form" method="POST">
                    <input type="text" id="input_login" placeholder="Username or email" name="login">
                    <input type="password" id="input_password" placeholder="Password" name="password">
                    <button type="submit">Log in</button>
                </form>
                <div class="or">
                    <div class="left or"></div>
                    <span class="or">OR</span>
                    <div class="right or"></div>
                </div>
            </div>
            <div class="bottom">
                <a href="index.php?action=visitor_home" class="visitor">Continue without login</a>
                <p class="error">Sorry, your password was incorrect. Please double-check your password.</p>
                <a href="index.php?action=password_reset" class="violet forgot">Forgot password?</a>
                <p>Don't have an account?<a href="index.php?action=register" class="violet"> Sign up</a></p>
            </div>
        </div>
    </main>
    <?php require_once "views/modale_cookies.php" ?>
    <?php require_once "views/footer.php" ?>
</body>
</html>