<!-- /views/register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
    <main>
        <div class="top">
            <h1>Register</h1>
            <form method="POST" action="index.php?action=register">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
        </div>
        <div class="bottom">
            
        </div>
    
    </main>
    <footer>
        <p>© 2025 Wayl Louaked . This project is licensed under the 
        <a href="https://opensource.org/licenses/MIT" target="_blank">MIT License</a>.</p>
    </footer>
</body>
</html>

