<?php

session_start();

use Router\Router;
use Globals\Globals;

require('../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
spl_autoload_register(function ($class) {
    $class = '../src/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($class)) {
        require_once($class);
    }
});

$globals = new Globals();

if (!isset($globals->getSESSION('user')['logged'])) {
    $_SESSION['user']['logged'] = false;
    $_SESSION['user']['role'] = 'visitor';
}

$router = new Router();
$router->execute();
