<?php
session_start();

echo session_save_path();

use Router\Router;

require('../vendor/autoload.php');
spl_autoload_register(function ($class) {
    $class = '../src/' . str_replace("\\", '/', $class) . '.php';
    if (is_file($class)) {
        require_once($class);
    } 
});

$router = new Router();
$router->execute();

?>