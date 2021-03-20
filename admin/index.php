<?php
if (!empty($_COOKIE['auth'])) {
    header('Location: ./explorer.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
</head>
<body>
    <div class="login">Log in (admin 12345)</div>

    <form method="POST" action="./login.php">
        <input type="text" name="login" placeholder="Login">
        <input type="password" name="password" placeholder="Password">
        <button>Ok</button>
    </form>
</body>
</html>