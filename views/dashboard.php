<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/dashboard.css"> 
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <script defer src="assets/js/hamburger.js"></script>
</head>
<body>
    <div class="overlay"></div>
    <nav>
        <img class="logo" src="assets/images/logo.png" alt="Camagru Logo" >
        <img src="assets/images/icon/Camagru_icon_black.png" alt="Camagru Logo black and white" class="home icon black">
        <ul>
            <li>
                <a href="#">
                    <span class="link-area home">
                        <img class="icon home" src="assets/images/icon/home.svg" alt="home icon">
                        <span class = "icon home nav">Home</span>
                    </span>
                </a>
            </li> 
            <li>
                <a href="#">
                    <span class="link-area search">
                        <img class="icon search" src="assets/images/icon/search.svg" alt="search icon">
                        <span class = "icon search nav">Search</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="link-area explore">
                        <img class="icon explore" src="assets/images/icon/compass.svg" alt="explore icon">
                        <span class = "icon explore nav">Explore</span>
                    </span>
                </a>
            </li>

            <li>
                <a href="#">
                    <span class="link-area notifications">
                        <img class="icon notifications" src="assets/images/icon/heart.svg" alt="profile icon">
                        <span class = "icon notifications nav">Notifications</span>
                    </span>
                </a>
            </li>

            <li>
                <a href="#">
                    <span class="link-area create">
                        <img class="icon create" src="assets/images/icon/create.svg" alt="create icon">
                        <span class = "icon create nav">Create</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="link-area profile">
                        <img class="icon profile" src="assets/images/icon/profile.svg" alt="profile icon">
                        <span class = "icon profile nav">Profile</span>
                    </span>
                </a>
            </li>
        </ul>
    </nav>
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

