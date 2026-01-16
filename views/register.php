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
    <div class="overlay"></div>


    <main>
        <div class="top">
            <img src="assets/images/logo.png" alt="Camagru Logo">
            <p class = "logo">Sign up to see photos from your friends.</p>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <p class="error email">Enter a valid email address.</p>
                
                <input type="password" name="password" placeholder="Password" required>
                <p class="error password">Create a password at least 6 characters long.</p>
                <p class="error uppercase">Password must contain at least one uppercase letter.</p>
                
                <input type="text" name="fullname" placeholder="Full name" required>
                <p class="error fullname_size">Full name must be 2-50 characters.</p>
                <p class="error fullname">Full name must contain only letters, spaces, hyphens, and apostrophes.</p>

                <input type="text" name="username" placeholder="Username" required>
                <p class="error username_size">Username must be 3-30 characters.</p>
                <p class="error username">Username must contain only letters, numbers, underscores, and periods.</p>
                <p class="error username_invailable">A user with that username already exists.</p>

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
    <?php require_once "modale_learn_more.php" ?>
</body>
</html>

