<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/email_signup.css"> 
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <script src="assets/js/email_signup.js" defer></script>
</head>
<body>
    <main>
        <div class="top">
            <img src="assets/images/icon/letter.svg" alt="Letter Icon">
            <p class="first">Enter Confirmation Code</p>
            <div class="top1">
                <p>Enter the confirmation code we sent to</p>
                <p>
                    <?php echo htmlspecialchars($_SESSION['user_email']); ?>. 
                    <a id="resend-link" href="#">Resend Code.</a>
                </p>
            </div>
            <form method="post" novalidate>
                <input type="text" placeholder="Confirmation Code" pattern="[0-9]{6}" maxlength="6" inputmode="numeric" name="code">
                <button type="submit">Next</button>
            </form>
            <div class="error">
                <p class = "error">That code isn't valid. You can request a new one.</p>
                <p class="error2">That code has expired (timeout).</p>
            </div>
            <div class = "top2">
                <p>You can also report content you believe is unlawful in </p>
                <p>your country without logging in.</p>
            </div>
        </div>
        <div class="bottom">
                <p>Have an account?</p>
                <a href="index.php?action=index"> Log in</a>
        </div>
    </main>
    <?php require_once "footer.php" ?>
    <div class = "snackbar">
        <p id="snackbar-success">A new verification code has been sent to your email.</p>
        <p id="snackbar-failure">Sorry! We're having trouble sending you a confirmation code right now. Please try again later.</p>
    </div>
</body>
</html>