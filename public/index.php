<?php  

use Router\Router;

require('../vendor/autoload.php');

$router = new Router($_GET['url']);
// $router->get('/', 'App\Controllers\BlogController@index');
// $router->get('/posts/:id', 'App\Controllers\PostController@show');

$router->get('/', 'App\Controllers\Home@displayHome');

$router->run();

?>