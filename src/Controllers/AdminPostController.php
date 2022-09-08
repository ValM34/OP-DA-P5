<?php

namespace Controllers;

use Router\Helpers;
use Models\ConnectDb;
use Globals\Globals;
use Models\Post;
use Models\Comment;
use Managers\AdminPostManager;

class AdminPostController
{
  private $pdo;
  private $helpers;

  public function __construct()
  {
    $connectDb = new ConnectDb();
    $this->pdo = $connectDb->connect();
    $this->globals = new Globals();
    $this->globals->getENV('adminLink');
    $this->helpers = new Helpers();
    $this->helpers->isAdmin();
    $this->path = $this->helpers->pathToPublic();
    $this->post = new Post();
    $this->comment = new Comment();
    $this->adminPostManager = new AdminPostManager();
  }

  // Affiche la page d'accueil de la page d'administration
  public function displayHomePage()
  {
    include_once __DIR__ . '/../templates/configTwig.php';
    $userSession = $this->helpers->isLogged();
    $twig->display('adminHomePage.twig', ['userSession' => $userSession, 'adminLink' => $this->globals->getENV('adminLink')]);
  }

  // Affiche la liste des articles
  public function displayPostsList()
  {
    $post = $this->adminPostManager->readAllPosts();
    $userSession = $this->helpers->isLogged();
    include_once __DIR__ . '/../templates/configTwig.php';
    $post = $this->helpers->dateConverter($post);
    $twig->display('adminPostList.twig', ['postList' => $post, 'pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->globals->getENV('adminLink')]);
  }

  // Change le statut d'un article pour le masquer sur le site
  public function hide($id_post)
  {
    $this->adminPostManager->hidePost($id_post);
    header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/touslesarticles');
  }

  // Change le statut d'un article pour le publier sur le site
  public function publish($id_post)
  {
    $this->adminPostManager->publishPost($id_post);
    header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/touslesarticles');
  }

  // Supprime un article
  public function delete($id_post)
  {
    $imgSrc = $this->adminPostManager->getImgSrc($id_post);
    if ($imgSrc !== null) {
      $pathToDeleteImg = __DIR__ . '/../../public/' . $imgSrc;
      unlink($pathToDeleteImg);
    }
    $this->adminPostManager->deletePost($id_post);
    header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/touslesarticles');
  }

  // Affiche la page pour ajouter un article
  public function displayAddPostPage()
  {
    $fetchAdminUsers = $this->adminPostManager->getAdmins();
    $userSession = $this->helpers->isLogged();
    for ($i = 0; $i < count($fetchAdminUsers); $i++) {
      if ($fetchAdminUsers[$i]['id'] === $userSession['id']) {
        $userSession['name'] = $fetchAdminUsers[$i]['name'];
        $userSession['surname'] = $fetchAdminUsers[$i]['surname'];
        break;
      }
    }
    include_once __DIR__ . '/../templates/configTwig.php';
    $twig->display('adminAddPost.twig', ['adminUsersList' => $fetchAdminUsers, 'pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->globals->getENV('adminLink')]);
  }

  // Crée un article
  public function addPost()
  {
    $userSession = $this->helpers->isLogged();
    $post['postTitle'] = htmlspecialchars($this->globals->getPOST('postTitle'), FILTER_FLAG_NO_ENCODE_QUOTES);
    $post['postContent'] = htmlspecialchars($this->globals->getPOST('postContent'), FILTER_FLAG_NO_ENCODE_QUOTES);
    $post['postChapo'] = htmlspecialchars($this->globals->getPOST('postChapo'), FILTER_FLAG_NO_ENCODE_QUOTES);
    $post['idUser'] = htmlspecialchars($this->globals->getPOST('idUser'), FILTER_FLAG_NO_ENCODE_QUOTES);
    $title = $post['postTitle'];
    $content = $post['postContent'];
    $chapo = $post['postChapo'];
    $idUser = $post['idUser'];

    if (empty($title) || empty($content) || empty($chapo)) {
      include_once __DIR__ . '/../templates/configTwig.php';
      $twig->display('adminAddPost.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->globals->getENV('adminLink'), 'errorMsg' => true]);
      return;
    }

    $file = $this->globals->getFILES('postFile');
    if ($file['size'] !== 0) {
      $maxSize = 3000000;
      $validExt = ['.jpg', '.jpeg', '.png'];
      $fileSize = $file['size'];
      $fileName = htmlspecialchars($file['name'], FILTER_FLAG_NO_ENCODE_QUOTES);
      $fileExt = '.' . strtolower(substr(strrchr($fileName, '.'), 1));
      $tmpName = $file['tmp_name'];
      $uniqueName = md5(uniqid(rand(), true));
      $fileName = __DIR__ . '/../../public/images/posts/' .  $uniqueName . $fileExt;

      if ($file['error'] || $fileSize > $maxSize || !in_array($fileExt, $validExt)) {
        include_once __DIR__ . '/../templates/configTwig.php';
        $twig->display('adminAddPost.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->globals->getENV('adminLink'), 'errorMsg' => true]);
        return;
      }

      move_uploaded_file($tmpName, $fileName);

      $src = 'images/posts/' . $uniqueName . $fileExt;

      $this->adminPostManager->addPost($idUser, $title, $content, $chapo, $src);
      header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/touslesarticles');
    } else {
      $this->adminPostManager->addPost($idUser, $title, $content, $chapo, null);
      header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/touslesarticles');
    }
  }

  // Affiche la page pour modifier un article
  public function displayUpdatePostPage($id_post)
  {
    $userSession = $this->helpers->isLogged();
    $fetchAdminUsers = $this->adminPostManager->getAdmins();
    $post =  $this->adminPostManager->getPostData($id_post);
    include_once __DIR__ . '/../templates/configTwig.php';
    $twig->display('adminUpdatePostPage.twig', ['adminUsersList' => $fetchAdminUsers, 'postList' => $post, 'pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->globals->getENV('adminLink')]);
  }

  // Modifie un article
  public function updatePost($id_post)
  {
    $userSession = $this->helpers->isLogged();
    $post['title'] = htmlspecialchars($this->globals->getPOST('title'), FILTER_FLAG_NO_ENCODE_QUOTES);
    $post['content'] = htmlspecialchars($this->globals->getPOST('content'), FILTER_FLAG_NO_ENCODE_QUOTES);
    $post['chapo'] = htmlspecialchars($this->globals->getPOST('chapo'), FILTER_FLAG_NO_ENCODE_QUOTES);
    $post['idUser'] = htmlspecialchars($this->globals->getPOST('idUser'), FILTER_FLAG_NO_ENCODE_QUOTES);
    $title = $post['title'];
    $content = $post['content'];
    $chapo = $post['chapo'];
    $idUser = $post['idUser'];

    if (empty($title) || empty($content) || empty($chapo)) {
      include_once __DIR__ . '/../templates/configTwig.php';
      $twig->display('adminAddPost.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->globals->getENV('adminLink'), 'errorMsg' => true]);
      return;
    }

    $file = $this->globals->getFILES('postFile');
    if ($file['size'] !== 0) {
      $maxSize = 3000000;
      $validExt = ['.jpg', '.jpeg', '.png'];
      $fileSize = $file['size'];
      $fileName = htmlspecialchars($file['name'], FILTER_FLAG_NO_ENCODE_QUOTES);
      $fileExt = '.' . strtolower(substr(strrchr($fileName, '.'), 1));
      $tmpName = $file['tmp_name'];
      $uniqueName = md5(uniqid(rand(), true));
      $fileName = __DIR__ . '/../../public/images/posts/' .  $uniqueName . $fileExt;
      if ($file['error'] || $fileSize > $maxSize || !in_array($fileExt, $validExt)) {
        include_once __DIR__ . '/../templates/configTwig.php';
        $twig->display('adminUpdatePostPage.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->globals->getENV('adminLink'), 'errorMsg' => true]);
        return;
      }
      $imgSrc = $this->adminPostManager->getImgSrc($id_post);

      // Suppression de l'ancienne l'image
      if ($imgSrc !== null) {
        $pathToDeleteImg = __DIR__ . '/../../public/' . $imgSrc;
        unlink($pathToDeleteImg);
      }

      move_uploaded_file($tmpName, $fileName);
      $src = 'images/posts/' . $uniqueName . $fileExt;
      $this->adminPostManager->updatePost($id_post, $idUser, $title, $content, $chapo, $src);
      header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/article/' . $id_post . '/afficher'); // Envoyer vers la page de vue de l'article
    } else {
      $this->adminPostManager->updatePost($id_post, $idUser, $title, $content, $chapo, null);
      header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/article/' . $id_post . '/afficher'); // Envoyer vers la page de vue de l'article
    }
  }

  // Change le statut en 'published' tous les articles séléctionnés
  public function publishSelected($idPostList)
  {
    $arrayIdPosts = explode('-', $idPostList);
    array_shift($arrayIdPosts);
    $this->adminPostManager->publishSelectedComments($arrayIdPosts);
    header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/touslesarticles');
  }

  // Change le statut en 'hidden' tous les articles séléctionnés
  public function hideSelected($idPostList)
  {
    $arrayIdPosts = explode('-', $idPostList);
    array_shift($arrayIdPosts);
    $this->adminPostManager->hideSelectedComments($arrayIdPosts);
    header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/touslesarticles');
  }

  // Supprime tous les articles séléctionnés
  public function deleteSelected($idPostList)
  {
    $arrayIdPosts = explode('-', $idPostList);
    array_shift($arrayIdPosts);
    $arrayImgSrc = $this->adminPostManager->deleteSelectedComments($arrayIdPosts);
    foreach ($arrayImgSrc as $imgToDelete) {
      var_dump($imgToDelete);
      $pathToDeleteImg = __DIR__ . '/../../public/' . $imgToDelete;
      unlink($pathToDeleteImg);
    }
    header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/touslesarticles');
  }

  // Affiche la liste des commentaires
  public function displayCommentsList()
  {
    $comments = $this->adminPostManager->readAllComments();
    $userSession = $this->helpers->isLogged();
    include_once __DIR__ . '/../templates/configTwig.php';
    $comments = $this->helpers->dateConverter($comments);
    $twig->display('adminCommentsList.twig', ['CommentsList' => $comments, 'pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->globals->getENV('adminLink')]);
  }

  // Change le statut d'un commentaire en 'published'
  public function publishComment($data)
  {
    $id_comment = $data;
    $this->adminPostManager->publishComment($id_comment);
    header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/commentaires');
  }

  // Change le statut d'un commentaire en 'rejected'
  public function rejectComment($data)
  {
    $id_comment = $data;
    $this->adminPostManager->rejectComment($id_comment);
    header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/commentaires');
  }

  // Supprime un commentaire
  public function deleteComment($data)
  {
    $id_comment = $data;
    $this->adminPostManager->deleteComment($id_comment);
    header('Location: ' . $this->path . $this->globals->getENV('adminLink') . '/commentaires');
  }
}
