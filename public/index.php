<?php

session_start();

use Router\Router;

require('../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
spl_autoload_register(function ($class) {
    $class = '../src/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($class)) {
        require_once($class);
    }
});

if(!isset($_SESSION['user']['logged'])) {
    $_SESSION['user']['logged'] = false;
    $_SESSION['user']['role'] = 'visitor';
}

/*if (isset($_SESSION['user'])) {
    if(isset($_SESSION['user']['logged']) & $_SESSION['user']['logged']) {
        echo 'Vous êtes connecté.';
    } else {
        echo 'Vous êtes déconnecté';
    }
} else {
    echo 'Vous êtes déconnecté';
}*/





$router = new Router();
$router->execute();
