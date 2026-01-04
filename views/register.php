<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/register.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <script defer src="assets/js/register.js"></script>
</head>
<body>
    <main>
        <div class="top">
            <img src="assets//images/logo.png" alt="Camagru Logo">
            <p class = "logo">Sign up to see photos from your friends.</p>
            <form method="POST" action="index.php?action=register">
                <input type="email" name="email" placeholder="Email" required>
                <p class="error email">Enter a valid email address.</p>
                <input type="password" name="password" placeholder="Password" required>
                <p class="error password">Create a password at least 6 characters long.</p>
                <input type="text" name="fullname" placeholder="Fullname" required>
                <input type="text" name="username" placeholder="Username" required>
                <p class="error username">This username isn't available.</p>
                <button type="submit">Next</button>
            </form>
            <p class="first">People who use our service may have uploaded your contact information to Camagru.<a href="#"> Learn More</a></p>
            <p class="second">By signing up, you agree to our <a href="#">Terms</a>. Learn how we collect, use and share your data in our <a href="#">Privacy Policy</a> and how we use cookies and similar technology in our <a href="#">Cookies Policy</a>.</p>
        </div>
        <div class="bottom">
            <p>Have an account?</p>
            <p><a href="index.php?action=index">Log in</a></p>
        </div> 
    </main>
    <?php require_once "footer.php" ?>
</body>
</html>

