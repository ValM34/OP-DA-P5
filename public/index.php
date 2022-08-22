<?php

session_start();

use Router\Router;
// test 
use Globals\Globals;
use Router\Helpers;
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
// $globals->request['test'] = '<b>test</b>';
// var_dump($globals->request);

/*$globals->GET['test'] = '<b>test</b>';
function tester($arg) {
    return htmlspecialchars(strip_tags($arg));
}*/
$helpers = new Helpers;
$globals->GET['test'] = $helpers->cleaner('<b>test</b>');
var_dump($globals->GET);


/*
var_dump(array_map('tester', $globals->GET));
var_dump($globals->GET);
*/
// fin test

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
