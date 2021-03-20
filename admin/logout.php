<?php
if (empty($_COOKIE['auth'])) {
    header('Location: ./index.php');
    exit;
}

setcookie('auth', '', time() - 10);
header('Location: ./');
exit;
