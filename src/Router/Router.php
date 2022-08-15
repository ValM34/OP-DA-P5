<?php

namespace Router;

use Controllers\HomeController;
use Controllers\UserController;
use Controllers\PostController;
use Controllers\AdminPostController;
use Controllers\ErrorPageController;

class Router
{
    private $url;
    private $adminLink;

    public function __construct()
    {
        // On enlève les '/' au début et à la fin de l'url (s'il y en a)
        $this->url = trim($_GET['url'], '/');
        echo $this->url;
        $this->adminLink = $_ENV['adminLink'];
    }

    public function execute()
    {
        // On divise l'url dans un tableau $data en fonction de "/" 
        // Exemple : post/ajouter => ['post', 'ajouter']
        $data = explode("/", $this->url);
        // En fonction du premier mot de l'url, je choisis d'initier une instance de classe donnée
        $numberOfPaths = count($data);
        $userSession['logged'] = false;
        if (isset($_SESSION['user']['logged'])) {
            $userSession = $_SESSION['user'];
            $userSession['adminLink'] = $this->adminLink;
        }


        // Je prépare ma variable qui me permet de créer mes liens dynamiques
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        switch ($data[0]) {
            case 'accueil':
                if (!isset($data[1])) {
                    $home = new HomeController();
                    $home->display($numberOfPaths, $userSession);
                } elseif ($data[1] === 'contact') {
                    $home = new HomeController();
                    $home->sendMail();
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $userSession);
                }
                break;
            case 'inscription':
                if (!isset($data[1])) {
                    $suscribe = new UserController();
                    $suscribe->displaySubscription($numberOfPaths, $userSession);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $userSession);
                }
                break;
            case 'inscription-valider':
                if (!isset($data[1])) {
                    $validateSuscribe = new UserController();
                    $validateSuscribe->suscribe($numberOfPaths);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $userSession);
                }
                break;
            case 'connexion':
                if (!isset($data[1])) {
                    $connexion = new UserController();
                    $connexion->displayConnexion($numberOfPaths, $userSession);
                } elseif ($data[1] === 'valider') {
                    $connexion = new UserController();
                    $connexion->connexion($userSession);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $userSession);
                }
                break;
            case 'deconnexion':
                if (empty($_SESSION['user']['logged'])) {
                    header("location: " . $path . "connexion");
                }
                if (!isset($data[1])) {
                    $deconnexion = new UserController();
                    $deconnexion->deconnexion($numberOfPaths);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $userSession);
                }
            case 'touslesarticles':
                if (!isset($data[1])) {
                    $postList = new PostController();
                    $postList->displayPostList($numberOfPaths, $userSession);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $userSession);
                }
                break;
            case 'post':
                if (!isset($data[2])) {
                    $post = new PostController();
                    $post->displayPost($numberOfPaths, $data[1], $userSession);
                } elseif ($data[2] === "add") {
                    $post = new PostController();
                    $post->addComment($data[1], $userSession);
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $userSession);
                }
                break;
            case $this->adminLink:
                if ($data[1] === 'touslesarticles' & !isset($data[3])) {
                    $adminPostList = new AdminPostController($numberOfPaths);
                    $adminPostList->display($numberOfPaths, $userSession);
                } elseif (isset($data[3])) {
                    if ($data[1] === 'article' & $data[3] === 'masquer') {
                        $adminHidePost = new AdminPostController($numberOfPaths);
                        $adminHidePost->hide($numberOfPaths, $data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'publier') {
                        $adminHidePost = new AdminPostController($numberOfPaths);
                        $adminHidePost->publish($numberOfPaths, $data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'supprimer') {
                        $adminHidePost = new AdminPostController($numberOfPaths);
                        $adminHidePost->delete($numberOfPaths, $data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'envoyer') {
                        $addPost = new AdminPostController($numberOfPaths);
                        $addPost->addPost($numberOfPaths);
                    } elseif ($data[1] === 'article' & $data[3] === 'afficher') {
                        $updatePost = new AdminPostController($numberOfPaths);
                        $updatePost->displayUpdatePostPage($numberOfPaths, $data[2], $userSession);
                    } elseif ($data[1] === 'article' & $data[3] === 'modifier') {
                        $updatePost = new AdminPostController($numberOfPaths);
                        $updatePost->updatePost($numberOfPaths, $data[2]);
                    } elseif ($data[1] === 'touslesarticles' & $data[2] === 'publishselected') {
                        $hideSelected = new AdminPostController($numberOfPaths);
                        $hideSelected->publishSelected($numberOfPaths, $userSession, $data[3]);
                    } elseif ($data[1] === 'touslesarticles' & $data[2] === 'hideselected') {
                        $hideSelected = new AdminPostController($numberOfPaths);
                        $hideSelected->hideSelected($numberOfPaths, $userSession, $data[3]);
                    } elseif ($data[1] === 'touslesarticles' & $data[2] === 'deleteselected') {
                        $deleteSelected = new AdminPostController($numberOfPaths);
                        $deleteSelected->deleteSelected($numberOfPaths, $userSession, $data[3]);
                    } else {
                        $errorPage = new ErrorPageController();
                        $errorPage->display($numberOfPaths, $userSession);
                    }
                } elseif (isset($data[2])) {
                    if ($data[1] === 'article' & $data[2] === 'ajouterunarticle') {
                        $addPostPage = new AdminPostController($numberOfPaths);
                        $addPostPage->displayAddPostPage($numberOfPaths, $userSession);
                    } else {
                        $errorPage = new ErrorPageController();
                        $errorPage->display($numberOfPaths, $userSession);
                    }
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display($numberOfPaths, $userSession);
                }
                break;
            default:
                $errorPage = new ErrorPageController();
                $errorPage->display($numberOfPaths, $userSession);
        }
    }
}
