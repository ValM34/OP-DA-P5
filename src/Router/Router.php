<?php

namespace Router;

use Controllers\HomeController;
use Controllers\PostListController;
use Controllers\SubscribeController;
use Controllers\ConnexionController;
use Controllers\PostController;

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
        // On divise l'url dans un tableau $data en fonction de "/" 
        // Exemple : post/ajouter => ['post', 'ajouter']
        $data = explode("/", $this->url);
        // En fonction du premier mot de l'url, je choisis d'initier une instance de classe donnée
        $numberOfPaths = count($data);
        // Je prépare ma variable qui me permet de créer mes liens dynamiques
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
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
            case 'inscription':
                $suscribe = new SubscribeController();
                $suscribe->display($numberOfPaths);
                break;
            case 'inscription-valider':
                $validateSuscribe = new SubscribeController();
                $validateSuscribe->suscribe($numberOfPaths);
                break;
            case 'connexion':
                /*if (!empty($_SESSION['logged'])) {
                    header("location: " . $path . "accueil");
                }*/
                if (!isset($data[1])) {
                    $connexion = new ConnexionController();
                    $connexion->display($numberOfPaths);
                } elseif ($data[1] === 'valider') {
                    $connexion = new ConnexionController();
                    $connexion->connexion();
                    $connexion->display($numberOfPaths);
                }
                break;
            case 'deconnexion':
                if (empty($_SESSION['logged'])) {
                    header("location: " . $path . "connexion");
                }
                if (!isset($data[1])) {
                    $deconnexion = new ConnexionController();
                    $deconnexion->deconnexion($numberOfPaths);
                }
            case 'touslesarticles':
                if (!isset($data[1])) {
                    $postList = new PostListController();
                    $postList->display($numberOfPaths);
                }
                break;
            case 'post':
                echo var_dump($data);
                if(!isset($data[2])) {
                    $post = new PostController();
                    $post->display($numberOfPaths, $data[1]);
                } elseif($data[2] === "add") {
                    $post = new PostController();
                    $post->add($numberOfPaths, $data[1]);
                }
        }
    }
}
