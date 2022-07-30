<?php

namespace Router;

use Controllers\HomeController;
use Controllers\PostListController;
use Controllers\SubscribeController;
use Controllers\ConnexionController;

class Router
{
    private $url;

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
        $numberOfPaths = count($data);
        switch ($data[0]) {
            case 'accueil':
                if (!isset($data[1])) {
                    $home = new HomeController();
                    $home->display($numberOfPaths);
                } elseif ($data[1] === 'contact') {
                    $home = new HomeController();
                    $home->sendMail();
                }
                break;
            case 'connectdb':
                $connectDb = new PostListController();
                $connectDb->setDbConnexion();
                break;
            case 'inscription':
                $suscribe = new SubscribeController();
                $suscribe->display($numberOfPaths);
                break;
            case 'inscription-valider':
                $validateSuscribe = new SubscribeController();
                $validateSuscribe->suscribe($numberOfPaths);
                break;
            case 'connexion':
                $connexionRead = new ConnexionController();
                $connexionRead->display($numberOfPaths);
        }
    }
}
