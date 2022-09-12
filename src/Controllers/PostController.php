<?php

namespace Controllers;

use Models\ConnectDb;
use Router\Helpers;
use Globals\Globals;
use Models\Post;
use Models\Comment;
use Managers\PostManager;

class PostController
{
  public function __construct()
  {
    $connectDb = new ConnectDb();
    $this->pdo = $connectDb->connect();
    $this->helpers = new Helpers();
    $this->path = $this->helpers->pathToPublic();
    $this->globals = new Globals;
    $this->postManager = new PostManager();
  }

  // Affiche la liste des articles
  public function displayPostList()
  {
    $postList = $this->postManager->readAll();
    $userSession = $this->helpers->isLogged();
    $postList = $this->helpers->dateConverter($postList);
    include_once __DIR__ . '/../templates/configTwig.php';
    if (true === $userSession['logged']) {
      $twig->display('postList.twig', ['postList' => $postList, 'pathToPublic' => $this->path, 'userSession' => $userSession]);
    } else {
      $twig->display('postList.twig', ['postList' => $postList, 'pathToPublic' => $this->path, 'userSession' => $userSession]);
    }
  }

  // Affiche l'article séléctionné
  public function displayPost($id_post, $errorMsg)
  {
    $post = $this->postManager->readOnePost($id_post);
    $post = $this->helpers->dateConverter($post);
    $comments = $this->postManager->readComments($id_post);
    $comments = $this->helpers->dateConverter($comments);
    $userSession = $this->helpers->isLogged();

    include __DIR__ . '/../templates/configTwig.php';
    $twig->display('post.twig', [
      'post' => $post,
      'comments' => $comments,
      'pathToPublic' => $this->path,
      'userSession' => $userSession,
      'errorMsg' => $errorMsg
    ]);
  }

  // Ajoute un commentaire
  public function addComment($id_post_owner)
  {
    $id = $id_post_owner;
    $userSession = $this->helpers->isLogged();
    if (true === $userSession['logged']) {
      $id_user = $_SESSION['user']['id'];
      $id_post = $id;
      $post = $this->globals->getPOST('content');
      $cleanedPOST = strip_tags($post);
      $content = htmlspecialchars($cleanedPOST, FILTER_FLAG_NO_ENCODE_QUOTES);

      if (empty($content)) {
        $errorMsg = true;
        include __DIR__ . '/../templates/configTwig.php';
        $this->displayPost($id, $errorMsg);
        return;
      }
      $this->postManager->addComment($id_post, $id_user, $content);
      $errorMsg = false;
      header('location: ' . $this->globals->getSERVER('HTTP_REFERER'));
    } else {
      $errorMsg = false;
      include __DIR__ . '/../templates/configTwig.php';
      $this->displayPost($id, $errorMsg);
      return;
    }
  }
}
