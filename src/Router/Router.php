<?php

namespace Router;

use Controllers\HomeController;
use Controllers\PostListController;
use Controllers\SubscribeController;
use Controllers\ConnexionController;
use Controllers\PostController;
use Controllers\AdminPostListController;
use Controllers\ErrorPageController;

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
        $isLogged = 0;
        if (isset($_SESSION['logged'])){
            $isLogged = $_SESSION['logged'];
            echo '<br>'.$isLogged;
        }

        
        // Je prépare ma variable qui me permet de créer mes liens dynamiques
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        switch ($data[0]) {
            case 'accueil':
                if (!isset($data[1])) {
                    $home = new HomeController();
                    $home->display($numberOfPaths, $isLogged);
                } elseif ($data[1] === 'contact') {
                    $home = new HomeController();
                    $home->sendMail();
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $isLogged);
                }
                break;
            case 'inscription':
                if (!isset($data[1])) {
                    $suscribe = new SubscribeController();
                    $suscribe->display($numberOfPaths, $isLogged);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $isLogged);
                }
                break;
            case 'inscription-valider':
                if (!isset($data[1])) {
                    $validateSuscribe = new SubscribeController();
                    $validateSuscribe->suscribe($numberOfPaths);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $isLogged);
                }
                break;
            case 'connexion':
                /*if (!empty($_SESSION['logged'])) {
                    header("location: " . $path . "accueil");
                }*/
                if (!isset($data[1])) {
                    $connexion = new ConnexionController();
                    $connexion->display($numberOfPaths, $isLogged);
                } elseif ($data[1] === 'valider') {
                    $connexion = new ConnexionController();
                    $connexion->connexion();
                    $connexion->display($numberOfPaths, $isLogged);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $isLogged);
                }
                break;
            case 'deconnexion':
                if (empty($_SESSION['logged'])) {
                    header("location: " . $path . "connexion");
                }
                if (!isset($data[1])) {
                    $deconnexion = new ConnexionController();
                    $deconnexion->deconnexion($numberOfPaths);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $isLogged);
                }
            case 'touslesarticles':
                if (!isset($data[1])) {
                    $postList = new PostListController();
                    $postList->display($numberOfPaths, $isLogged);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $isLogged);
                }
                break;
            case 'post':
                if (!isset($data[2])) {
                    $post = new PostController();
                    $post->display($numberOfPaths, $data[1], $isLogged);
                } elseif ($data[2] === "add") {
                    $post = new PostController();
                    $post->addComment($numberOfPaths, $data[1], $isLogged);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $isLogged);
                }
                break;
            case 'admin-15645':
                if ($data[1] === 'touslesarticles' & !isset($data[3])) {
                    $adminPostList = new AdminPostListController();
                    $adminPostList->display($numberOfPaths, $isLogged);
                } elseif (isset($data[3])) {
                    if ($data[1] === 'article' & $data[3] === 'masquer') {
                        $adminHidePost = new AdminPostListController();
                        $adminHidePost->hide($numberOfPaths, $data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'publier') {
                        $adminHidePost = new AdminPostListController();
                        $adminHidePost->publish($numberOfPaths, $data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'supprimer') {
                        $adminHidePost = new AdminPostListController();
                        $adminHidePost->delete($numberOfPaths, $data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'envoyer') {
                        $addPost = new AdminPostListController();
                        $addPost->addPost($numberOfPaths);
                    } elseif ($data[1] === 'article' & $data[3] === 'afficher') {
                        $updatePost = new AdminPostListController();
                        $updatePost->displayUpdatePostPage($numberOfPaths, $data[2], $isLogged);
                    } elseif ($data[1] === 'article' & $data[3] === 'modifier') {
                        $updatePost = new AdminPostListController();
                        $updatePost->updatePost($numberOfPaths, $data[2]);
                    } else {
                        $errorPage = new ErrorPageController();
                        $errorPage->display($numberOfPaths, $isLogged);
                    }
                } elseif ($data[1] === 'article' & $data[2] === 'ajouterunarticle') {
                    $addPostPage = new AdminPostListController();
                    $addPostPage->displayAddPostPage($numberOfPaths, $isLogged);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $isLogged);
                }
                break;
            default:
                $errorPage = new ErrorPageController();
                $errorPage->display($numberOfPaths, $isLogged);
        }
    }
}
