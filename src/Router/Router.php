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
        // En fonction du premier mot de l'url, je choisis d'initier une instance de classe donnée
        echo var_dump($data);
        $nombreDeMots = count($data);
        switch ($data[0]) {
            case 'accueil':
                if (!isset($data[1])) {
                    $home = new Home();
                    $nombreDeMots = count($data);
                    $home->displayHome($nombreDeMots);
                } elseif ($data[1] === 'contact') {
                    $container = new ContactController();
                    $container->displayContainer();
                }
                break;
            case 'connectdb':
                $connectDb = new PostListController();
                $connectDb->setDbConnexion();
                break;
            case 'inscription':
                $suscribe = new SuscribeControllerRead();
                $suscribe->setSuscribePage($nombreDeMots);
                break;
            case 'inscription-valider':
                $validateSuscribe = new SuscribeControllerPost();
                $validateSuscribe->suscribe($nombreDeMots);
                break;
        }
    }
}