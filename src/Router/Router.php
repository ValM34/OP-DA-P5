<?php

namespace Router;

use Controllers\Home;
use Controllers\ContactController;
use Controllers\PostListController;

class Router
{

    private $url;
    private $routes = [];

    public function __construct()
    {
        $this->url = trim($_GET['url'], '/');
    }

    public function execute()
    {
        echo $this->url;
        $data = explode("/", $this->url);
        print_r($data);
        echo $data[2];
        switch ($data[0]) {
            case 'post':
                if ($data[2] === 'create') {
                    $home = new Home();
                    $home->displayHome();
                    echo $data[1];
                } elseif ($data[2] === 'Contact.php') {
                    $container = new ContactController();
                    $container->displayContainer();
                }
                break;
            case 'autre':
                $home = new Home();
                $home->displayHome();
                echo 'autrre';
                break;
            case 'connectdb':
                $connectDb = new PostListController();
                $connectDb->setDbConnexion();
                break;
        }
        /*if ($data[0] === 'post') {
            $home = new Home();
            if($data[2] === 'display') {
                $home->displayHome();
                echo $data[1];
            } elseif ($data[2] === 'create'){
                echo 'create';
            }
            // $home->displayHome();
        }*/
    }

    public function get(string $path, string $action)
    {
        $this->routes['GET'][] = new Route($path, $action);
    }
    /*
    public function run()
    {
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->matches($this->url)) {
                return $route->execute();
            }
        }

        throw header('HTTP/1.0 404 Not Found');
    }
    */
}
