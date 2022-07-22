<?php  

use Router\Router;

require('../vendor/autoload.php');
// print_r($_GET);
spl_autoload_register(function ($class) {
    $class = '../src/' . str_replace("\\", '/', $class) . '.php';
    if (is_file($class)) {
        require_once($class);
    } 
});

$router = new Router();

$router->execute();

// $router->get('/', 'App\Controllers\BlogController@index');
// $router->get('/posts/:id', 'App\Controllers\PostController@show');

// $router->get('/aa', 'App\Controllers\Home@displayHome');
// $router->get('/bb', 'App\Controllers\NamespaceTest@getContent2');

// $router->run();



?>