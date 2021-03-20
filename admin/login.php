<?php 
if (empty($_REQUEST['login']) || empty($_REQUEST['password'])) {
    header('Location: ./');
    exit;
}

$config = parse_ini_file('./config.ini', true);

if (crypt($_REQUEST['login'], 'fALW0d_da') === $config['login'] && 
    password_verify($_REQUEST['password'], $config['password']) === true) {
        setcookie('auth', 'true', time() + 1800);
        header('Location: ./explorer.php');
        exit;
    } else {
        header('Location: ./');
        exit;
    }