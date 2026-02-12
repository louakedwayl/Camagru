<script defer src="assets/js/navbar.js"></script>
<link rel="stylesheet" href="assets/css/navbar.css">
<link rel="stylesheet" href="assets/css/visitor_navbar.css">
<nav>
    <!-- LOGO -->
    <img class="logo" src="assets/images/logo.png" alt="Camagru Logo">
    <img class="home icon black" src="assets/images/icon/Camagru_icon_black.png" alt="Camagru Logo black and white">

    <!-- MAIN MENU -->
    <ul>
        <li>
            <a href="index.php?action=visitor_home">
                <span class="link-area home">
                    <img class="icon home house" src="assets/images/icon/home.svg" alt="home icon">
                    <span class="icon home nav">Home</span>
                </span>
            </a>
        </li>
        <li>
            <a href="#" id="search-link">
                <span class="link-area search">
                    <img class="icon search" src="assets/images/icon/search.svg" alt="search icon">
                    <span class="icon search nav">Search</span>
                </span>
            </a>
        </li>
        <li>
            <a href="index.php?action=visitor_explore">
                <span class="link-area explore">
                    <img class="icon explore" src="assets/images/icon/compass.svg" alt="explore icon">
                    <span class="icon explore nav">Explore</span>
                </span>
            </a>
        </li>
    </ul>
    <?php require_once "visitor_corner.php" ?>
</nav>
<?php require_once "search_bar.php" ?>