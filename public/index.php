<?php

session_start();

use Router\Router;

$array = '           ';
if(empty($array)) {
    echo 'empty !!!!!!!!!!';
}
echo preg_match('/^\s*$/', $array);
if(count(str_split($array)) < 10 || preg_match('/^\s*$/', $array) === 1) {
    echo 'erreur';
} else {
    echo "c'est bon !";
}

require('../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
spl_autoload_register(function ($class) {
    $class = '../src/' . str_replace("\\", '/', $class) . '.php';
    if (is_file($class)) {
        require_once($class);
    } 
});

$router = new Router();
$router->execute();

?>