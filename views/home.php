<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/home.css"> 
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <script defer src="assets/js/hamburger.js"></script>
</head>
<body>
    <div class="overlay"></div>
    <?php require_once __DIR__ . '/navbar.php'; ?>
    <main>
    </main>
    <div class="corner">
        <a href="#" class = "icon more">
            <img class="icon more" src="assets/images/icon/more.svg" alt="more icon">
            <span class = "icon more">More</span>
        </a>
          <div class="hamburger">
            <ul>
                <li>
                    <a href="#" class= "report hamburger">
                        <span class="link-area-hamburger report">
                            <img class="icon report" src="assets/images/icon/report.svg" alt="report icon">
                            <span class = "report">Report a problem</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" >
                        <span class = "link-area-hamburger logout">Log out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php require_once "modale_report.php" ?>
    <?php require_once "footer.php" ?>
</body>
</html>

