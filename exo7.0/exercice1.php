<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $_SESSION['username'] = htmlspecialchars($_POST['username']);
}
if (isset($_SESSION['username'])) {
    echo "<h1>Bonjour " . $_SESSION['username'] . "</h1>";
} else {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 50px; }
            .login-form { max-width: 300px; }
            label { display: block; margin-bottom: 5px; }
            input[type="text"] { padding: 5px; width: 200px; margin-bottom: 10px; }
            button { padding: 5px 15px; cursor: pointer; }
        </style>
    </head>
    <body>
        <div class="login-form">
            <h2>Login</h2>
            <form method="POST" action="">
                <label for="username">Username :</label>
                <input type="text" id="username" name="username" required>
                <button type="submit">Valider</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>