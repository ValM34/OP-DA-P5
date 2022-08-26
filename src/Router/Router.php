<?php

namespace Router;

use Controllers\HomeController;
use Controllers\UserController;
use Controllers\PostController;
use Controllers\AdminPostController;
use Controllers\ErrorPageController;
use Globals\Globals;

class Router
{
    private $url;
    // private $adminLink;

    public function __construct()
    {
        $this->globals = new Globals();
        // On enlève les '/' au début et à la fin de l'url (s'il y en a)
        $this->url = trim($this->globals->getGET('url'), '/');
        $this->helpers = new Helpers();
        $this->path = $this->helpers->pathToPublic();
    }

    // Execute une page en fonction du chemin de l'url
    public function execute()
    {
        // On divise l'url dans un tableau $data en fonction de "/" 
        // Exemple : post/ajouter => ['post', 'ajouter']
        $data = explode('/', $this->url);
        // En fonction du premier mot de l'url, je choisis d'initier une instance de classe donnée
        // $numberOfPaths = count($data);

        // test (((((((((((((((((((((((())))))))))))))))))))))))
        // $userSession['logged'] = false;
        // fin test (((((((((((((((((((((((((((((((((((())))))))))))))))))))))))))))))))))))


        // Je prépare ma variable qui me permet de créer mes liens dynamiques
        // $pathToPublic = new Helpers();
        // $path = $pathToPublic->pathToPublic($numberOfPaths);
        switch ($data[0]) {
            case 'accueil':
                if (!isset($data[1])) {
                    $home = new HomeController();
                    $home->display('');
                } elseif ($data[1] === 'contact') {
                    $home = new HomeController();
                    $home->sendMail(urlencode('Votre email a bien été envoyé'));
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display();
                }
                break;
            case 'inscription':
                if (!isset($data[1])) {
                    $suscribe = new UserController();
                    $suscribe->displaySubscription();
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display();
                }
                break;
            case 'inscription-valider':
                if (!isset($data[1])) {
                    $validateSuscribe = new UserController();
                    $validateSuscribe->suscribe();
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display();
                }
                break;
            case 'connexion':
                if (!isset($data[1])) {
                    $connexion = new UserController();
                    $connexion->displayConnexion();
                } elseif ($data[1] === 'valider') {
                    $connexion = new UserController();
                    $connexion->connexion();
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display();
                }
                break;
            case 'deconnexion':
                if (!isset($data[1])) {
                    $deconnexion = new UserController();
                    $deconnexion->deconnexion();
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display();
                }
            case 'touslesarticles':
                if (!isset($data[1])) {
                    $postList = new PostController();
                    $postList->displayPostList();
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display();
                }
                break;
            case 'post':
                if (!isset($data[2])) {
                    $post = new PostController();
                    $post->displayPost($data[1], false);
                } elseif ($data[2] === 'add') {
                    $post = new PostController();
                    $post->addComment($data[1], );
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display();
                }
                break;
            case $this->globals->getENV('adminLink'):
                if (!isset($data[1])) {
                    $adminHomePage = new AdminPostController();
                    $adminHomePage->displayHomePage();
                } elseif ($data[1] === 'touslesarticles' & !isset($data[3])) {
                    $adminPostList = new AdminPostController();
                    $adminPostList->displayPostsList();
                } elseif ($data[1] === 'commentaires' & !isset($data[2])) {
                    $adminCommentsList = new AdminPostController();
                    $adminCommentsList->displayCommentsList();
                } elseif ($data[1] === 'commentaires' & $data[2] === 'publier') {
                    $adminCommentsPublish = new AdminPostController();
                    $adminCommentsPublish->publishComment($data[3]);
                } elseif ($data[1] === 'commentaires' & $data[2] === 'rejeter') {
                    $adminRejectComment = new AdminPostController();
                    $adminRejectComment->rejectComment($data[3]);
                } elseif ($data[1] === 'commentaires' & $data[2] === 'supprimer') {
                    $adminCommentsDelete = new AdminPostController();
                    $adminCommentsDelete->deleteComment($data[3]);
                } elseif (isset($data[3])) {
                    if ($data[1] === 'article' & $data[3] === 'masquer') {
                        $adminHidePost = new AdminPostController();
                        $adminHidePost->hide($data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'publier') {
                        $adminHidePost = new AdminPostController();
                        $adminHidePost->publish($data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'supprimer') {
                        $adminHidePost = new AdminPostController();
                        $adminHidePost->delete($data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'envoyer') {
                        $addPost = new AdminPostController();
                        $addPost->addPost();
                    } elseif ($data[1] === 'article' & $data[3] === 'afficher') {
                        $updatePost = new AdminPostController();
                        $updatePost->displayUpdatePostPage($data[2]);
                    } elseif ($data[1] === 'article' & $data[3] === 'modifier') {
                        $updatePost = new AdminPostController();
                        $updatePost->updatePost($data[2]);
                    } elseif ($data[1] === 'touslesarticles' & $data[2] === 'publishselected') {
                        $hideSelected = new AdminPostController();
                        $hideSelected->publishSelected($data[3]);
                    } elseif ($data[1] === 'touslesarticles' & $data[2] === 'hideselected') {
                        $hideSelected = new AdminPostController();
                        $hideSelected->hideSelected($data[3]);
                    } elseif ($data[1] === 'touslesarticles' & $data[2] === 'deleteselected') {
                        $deleteSelected = new AdminPostController();
                        $deleteSelected->deleteSelected($data[3]);
                    } else {
                        $errorPage = new ErrorPageController();
                        $errorPage->display();
                    }
                } elseif (isset($data[2])) {
                    if ($data[1] === 'article' & $data[2] === 'ajouterunarticle') {
                        $addPostPage = new AdminPostController();
                        $addPostPage->displayAddPostPage();
                    } else {
                        $errorPage = new ErrorPageController();
                        $errorPage->display();
                    }
                } else {
                    $errorPage = new ErrorPageController();
                    $errorPage->display();
                }
                break;
            default:
                $errorPage = new ErrorPageController();
                $errorPage->display();
        }
    }
}
