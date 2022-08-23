<?php

session_start();

use Router\Router;
// test 
use Globals\Globals;
// fin test

require('../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
spl_autoload_register(function ($class) {
    $class = '../src/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($class)) {
        require_once($class);
    }
});

// test

$globals = new Globals;
$session = $globals->getSESSION();

// fin test

if (!isset($session['user']['logged'])) {
    $session['user']['logged'] = false;
    $session['user']['role'] = 'visitor';
}

$router = new Router();
$router->execute();
