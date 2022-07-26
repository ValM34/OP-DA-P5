<?php

namespace Router;

use Controllers\Home;
use Controllers\ContactController;
use Controllers\PostListController;
use Controllers\Suscribe\SuscribeControllerRead;
use Controllers\Suscribe\SuscribeControllerPost;

class Router
{

    private $url;
    private $routes = [];

    public function __construct()
    {
        // On enlève les '/' au début et à la fin de l'url (s'il y en a)
        $this->url = trim($_GET['url'], '/');
    }

    public function execute()
    {
        echo $this->url;
        // On divise l'url dans un tableau $data en fonction de "/" 
        // Exemple : post/ajouter => ['post', 'ajouter']
        $data = explode("/", $this->url);
        print_r($data);
        echo $data[2];
        // En fonction du premier mot de l'url, je choisis d'initier une instance de classe donnée
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
            case 'inscription':
                $suscribe = new SuscribeControllerRead();
                $suscribe->setSuscribePage();
                break;
            case 'inscription-valider':
                $validateSuscribe = new SuscribeControllerPost();
                $validateSuscribe->suscribe();
                break;
        }
    }
    /*
    public function get(string $path, string $action)
    {
        $this->routes['GET'][] = new Route($path, $action);
    }*/
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
