<?php

namespace Controllers;

use Router\Helpers;
use Models\ConnectDb;
use Globals\Globals;

class AdminPostController
{
    private $pdo;
    private $helpers;
    private $adminLink;

    public function __construct()
    {
        $connectDb = new ConnectDb();
        $this->pdo = $connectDb->connect();
        $this->helpers = new Helpers();
        $this->helpers->isAdmin();
        $this->adminLink = $_ENV['adminLink'];
        $this->path = $this->helpers->pathToPublic();
        $this->globals = new Globals();
    }

    // Affiche la page d'accueil de la page d'administration
    public function displayHomePage()
    {
        include_once(__DIR__ . '/../templates/configTwig.php');
        $userSession = $this->helpers->isLogged();
        $twig->display('adminHomePage.twig', ['userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    // Affiche la liste des articles
    public function displayPostsList()
    {
        $getPostListQuery = 'SELECT * FROM blog_posts ORDER BY created_at desc;';
        $getPostList = $this->pdo->prepare($getPostListQuery);
        $getPostList->execute();
        $fetchPostList = $getPostList->fetchAll();
        $userSession = $this->helpers->isLogged();
        include_once(__DIR__ . '/../templates/configTwig.php');
        $fetchPostList = $this->helpers->dateConverter($fetchPostList);
        $twig->display('adminPostList.twig', ['postList' => $fetchPostList, 'pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    // Change le statut d'un article pour le masquer sur le site
    public function hide($id_post)
    {
            $hidePostQuery = 'UPDATE blog_posts SET status = "hidden" WHERE id = :id;';
            $hidePost = $this->pdo->prepare($hidePostQuery);
            $hidePost->execute(['id' => $id_post]);
            header('Location: ' . $this->path . $this->adminLink . '/touslesarticles');
    }

    // Change le statut d'un article pour le publier sur le site
    public function publish($id_post)
    {
            $publishPostQuery = 'UPDATE blog_posts SET status = "published" WHERE id = :id;';
            $publishPost = $this->pdo->prepare($publishPostQuery);
            $publishPost->execute(['id' => $id_post]);
            header('Location: ' . $this->path . $this->adminLink . '/touslesarticles');
    }

    // Supprime un article
    public function delete($id_post)
    {
        $getImgSrcQuery = 'SELECT img_src FROM blog_posts WHERE id = :id;';
        $getImgSrc = $this->pdo->prepare($getImgSrcQuery);
        $getImgSrc->execute(['id' => $id_post]);
        $fetchImgSrc = $getImgSrc->fetchAll();

        if ($fetchImgSrc[0]['img_src'] !== null) {
            $pathToDeleteImg = __DIR__ . '/../../public/' . $fetchImgSrc[0]['img_src'];
            unlink($pathToDeleteImg);
        }

        $hidePostQuery = 'DELETE FROM blog_posts WHERE id = :id;';
        $hidePost = $this->pdo->prepare($hidePostQuery);
        $hidePost->execute(['id' => $id_post]);
        header('Location: ' . $this->path . $this->adminLink . '/touslesarticles');
    }

    // Affiche la page pour ajouter un article
    public function displayAddPostPage()
    {
        $displayAdminUsersQuery = 'SELECT id, name, surname FROM users WHERE role = "admin";';
        $displayAdminUsers = $this->pdo->prepare($displayAdminUsersQuery);
        $displayAdminUsers->execute();
        $fetchAdminUsers = $displayAdminUsers->fetchAll();
        $userSession = $this->helpers->isLogged();

        for ($i = 0; $i < count($fetchAdminUsers); $i++) {
            if ($fetchAdminUsers[$i]['id'] === $userSession['id']) {
                $userSession['name'] = $fetchAdminUsers[$i]['name'];
                $userSession['surname'] = $fetchAdminUsers[$i]['surname'];
                break;
            }
        }

        include_once(__DIR__ . '/../templates/configTwig.php');
        $twig->display('adminAddPost.twig', ['adminUsersList' => $fetchAdminUsers, 'pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    // Crée un article
    public function addPost()
    {
        $userSession = $this->helpers->isLogged();
        $post['postTitle'] = htmlspecialchars($this->globals->getPOST('postTitle'));
        $post['postContent'] = htmlspecialchars($this->globals->getPOST('postContent'));
        $post['postChapo'] = htmlspecialchars($this->globals->getPOST('postChapo'));
        $post['idUser'] = htmlspecialchars($this->globals->getPOST('idUser'));
        $title = $post['postTitle'];
        $content = $post['postContent'];
        $chapo = $post['postChapo'];
        $idUser = $post['idUser'];

        if (empty($title) || empty($content) || empty($chapo)) {
            include_once(__DIR__ . '/../templates/configTwig.php');
            $twig->display('adminAddPost.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->adminLink, 'errorMsg' => true]);
            return;
        }

        if ($_FILES['postFile']['size'] !== 0) {
            $maxSize = 3000000;
            $validExt = ['.jpg', '.jpeg', '.png'];
            $fileSize = $_FILES['postFile']['size'];
            $fileName = htmlspecialchars($_FILES['postFile']['name']);
            $fileExt = '.' . strtolower(substr(strrchr($fileName, '.'), 1));
            $tmpName = $_FILES['postFile']['tmp_name'];
            $uniqueName = md5(uniqid(rand(), true));
            $fileName = __DIR__ . '/../../public/images/posts/' .  $uniqueName . $fileExt;
            if ($_FILES['postFile']['error'] || $fileSize > $maxSize || !in_array($fileExt, $validExt)) {
                include_once(__DIR__ . '/../templates/configTwig.php');
                $twig->display('adminAddPost.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->adminLink, 'errorMsg' => true]);
                return;
            }

            move_uploaded_file($tmpName, $fileName);

            $src = 'images/posts/' . $uniqueName . $fileExt;

            $addPostQuery = 'INSERT INTO blog_posts (idUser, title, content, chapo, img_src) VALUES (:idUser, :title, :content, :chapo, :img_src);';
            $addPost = $this->pdo->prepare($addPostQuery);
            $addPost->execute([
                'idUser' => $idUser,
                'title' => $title,
                'content' => $content,
                'chapo' => $chapo,
                'img_src' => $src
            ]);
            header('Location: ' . $this->path . $this->adminLink . '/touslesarticles');
        } else {
            $addPostQuery = 'INSERT INTO blog_posts (idUser, title, content, chapo) VALUES (:idUser, :title, :content, :chapo);';
            $addPost = $this->pdo->prepare($addPostQuery);
            $addPost->execute([
                'idUser' => $idUser,
                'title' => $title,
                'content' => $content,
                'chapo' => $chapo
            ]);
            header('Location: ' . $this->path . $this->adminLink . '/touslesarticles');
        }
    }

    // Affiche la page pour modifier un article
    public function displayUpdatePostPage($id_post)
    {
        $userSession = $this->helpers->isLogged();
        $displayAdminUsersQuery = 'SELECT id, name, surname FROM users WHERE role = "admin";';
        $displayAdminUsers = $this->pdo->prepare($displayAdminUsersQuery);
        $displayAdminUsers->execute();
        $fetchAdminUsers = $displayAdminUsers->fetchAll();

        $displayUpdatePostQuery = '
        SELECT 
        U.name, U.surname,
        B.*
        FROM comments C, users U, blog_posts B
        WHERE B.idUser = U.id
        AND B.id = :id
        ;';
        $displayUpdatePost = $this->pdo->prepare($displayUpdatePostQuery);
        $displayUpdatePost->execute(['id' => $id_post]);
        $fetchPost = $displayUpdatePost->fetchAll();

        include_once(__DIR__ . '/../templates/configTwig.php');
        $twig->display('adminUpdatePostPage.twig', ['adminUsersList' => $fetchAdminUsers, 'postList' => $fetchPost[0], 'pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    // Modifie un article
    public function updatePost($id_post)
    {
        $userSession = $this->helpers->isLogged();
        $post['title'] = htmlspecialchars($this->globals->getPOST('title'));
        $post['content'] = htmlspecialchars($this->globals->getPOST('content'));
        $post['chapo'] = htmlspecialchars($this->globals->getPOST('chapo'));
        $post['idUser'] = htmlspecialchars($this->globals->getPOST('idUser'));
        $title = $post['title'];
        $content = $post['content'];
        $chapo = $post['chapo'];
        $idUser = $post['idUser'];

        if (empty($title) || empty($content) || empty($chapo)) {
            include_once(__DIR__ . '/../templates/configTwig.php');
            $twig->display('adminAddPost.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->adminLink, 'errorMsg' => true]);
            return;
        }

        if ($_FILES['postFile']['size'] !== 0) {
            $maxSize = 3000000;
            $validExt = ['.jpg', '.jpeg', '.png'];
            $fileSize = $_FILES['postFile']['size'];
            $fileName = htmlspecialchars($_FILES['postFile']['name']);
            $fileExt = '.' . strtolower(substr(strrchr($fileName, '.'), 1));
            $tmpName = $_FILES['postFile']['tmp_name'];
            $uniqueName = md5(uniqid(rand(), true));
            $fileName = __DIR__ . '/../../public/images/posts/' .  $uniqueName . $fileExt;
            if ($_FILES['postFile']['error'] || $fileSize > $maxSize || !in_array($fileExt, $validExt)) {
                include_once(__DIR__ . '/../templates/configTwig.php');
                $twig->display('adminUpdatePostPage.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->adminLink, 'errorMsg' => true]);
                return;
            }
            $getImgSrcQuery = 'SELECT img_src FROM blog_posts WHERE id = :id;';
            $getImgSrc = $this->pdo->prepare($getImgSrcQuery);
            $getImgSrc->execute(['id' => $id_post]);
            $fetchImgSrc = $getImgSrc->fetchAll();
            // Suppression de l'ancienne l'image
            if ($fetchImgSrc[0]['img_src'] !== null) {
                $pathToDeleteImg = __DIR__ . '/../../public/' . $fetchImgSrc[0]['img_src'];
                unlink($pathToDeleteImg);
            }

            move_uploaded_file($tmpName, $fileName);
            $src = 'images/posts/' . $uniqueName . $fileExt;

            $updatePostQuery = 'UPDATE blog_posts SET title = :title, content = :content, chapo = :chapo, img_src = :img_src, idUser = :idUser, updated_at = CURRENT_TIMESTAMP WHERE id = :id;';
            $updatePost = $this->pdo->prepare($updatePostQuery);
            $updatePost->execute([
                'id' => $id_post,
                'title' => $title,
                'content' => $content,
                'chapo' => $chapo,
                'img_src' => $src,
                'idUser' => $idUser
            ]);
            header('Location: ' . $this->path . $this->adminLink . '/article/' . $id_post . '/afficher'); // Envoyer vers la page de vue de l'article
        } else {
            $updatePostQuery = 'UPDATE blog_posts SET title = :title, content = :content, chapo = :chapo, idUser = :idUser, updated_at = CURRENT_TIMESTAMP WHERE id = :id;';
            $updatePost = $this->pdo->prepare($updatePostQuery);
            $updatePost->execute([
                'id' => $id_post,
                'title' => $title,
                'content' => $content,
                'chapo' => $chapo,
                'idUser' => $idUser
            ]);
            header('Location: ' . $this->path . $this->adminLink . '/article/' . $id_post . '/afficher'); // Envoyer vers la page de vue de l'article
        }
    }

    /*
    public function isUser($twig)
    {
        $userSession = $this->helpers->isLogged();
        $twig->display('home.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }*/

    // Change le statut en 'published' tous les articles séléctionnés
    public function publishSelected($idPostList)
    {
        $arrayIdPosts = explode('-', $idPostList);
        array_shift($arrayIdPosts);
        $publishSelectedQuery = 'UPDATE blog_posts SET status = "published" WHERE ID = :id_post;';
        $publishSelected = $this->pdo->prepare($publishSelectedQuery);
        foreach ($arrayIdPosts as $id_post) {
            $publishSelected->execute([
                'id_post' => $id_post
            ]);
        }
        header('Location: ' . $this->path . $this->adminLink . '/touslesarticles');
    }

    // Change le statut en 'hidden' tous les articles séléctionnés
    public function hideSelected($idPostList)
    {
        $arrayIdPosts = explode('-', $idPostList);
        array_shift($arrayIdPosts);
        $hideSelectedQuery = 'UPDATE blog_posts SET status = "hidden" WHERE ID = :id_post;';
        $hideSelected = $this->pdo->prepare($hideSelectedQuery);
        foreach ($arrayIdPosts as $id_post) {
            $hideSelected->execute([
                'id_post' => $id_post
            ]);
        }
        header('Location: ' . $this->path . $this->adminLink . '/touslesarticles');
    }

    // Supprime tous les articles séléctionnés
    public function deleteSelected($idPostList)
    {
        $arrayIdPosts = explode('-', $idPostList);
        array_shift($arrayIdPosts);
        $deleteSelectedQuery = 'DELETE FROM blog_posts WHERE ID = :id_post;';
        $deleteSelected = $this->pdo->prepare($deleteSelectedQuery);
        $getImgSrcQuery = 'SELECT img_src FROM blog_posts WHERE id = :id;';
        $getImgSrc = $this->pdo->prepare($getImgSrcQuery);
        foreach ($arrayIdPosts as $id_post) {
            $getImgSrc->execute(['id' => $id_post]);
            $fetchImgSrc = $getImgSrc->fetchAll();
            if ($fetchImgSrc[0]['img_src'] !== null) {
                $pathToDeleteImg = __DIR__ . '/../../public/' . $fetchImgSrc[0]['img_src'];
                unlink($pathToDeleteImg);
            }
            $deleteSelected->execute([
                'id_post' => $id_post
            ]);
        }
        $path = $this->helpers->pathToPublic();
        header('Location: ' . $this->path . $this->adminLink . '/touslesarticles');
    }

    // Affiche la liste des commentaires
    public function displayCommentsList()
    {
        $getCommentsListQuery = '
            SELECT U.name, U.surname, C.id as commentId, C.created_at, C.updated_at, C.content, C.status, B.title as postTitle, B.id as postId 
            FROM comments C, users U, blog_posts B
            WHERE C.id_user = U.id
            AND C.id_post = B.id
            AND C.id_user = B.idUser
            ORDER BY created_at;
        ';
        $getCommentsList = $this->pdo->prepare($getCommentsListQuery);
        $getCommentsList->execute();
        $fetchCommentsList = $getCommentsList->fetchAll();
        $userSession = $this->helpers->isLogged();
        include_once(__DIR__ . '/../templates/configTwig.php');
        $fetchCommentsList = $this->helpers->dateConverter($fetchCommentsList);
        $twig->display('adminCommentsList.twig', ['CommentsList' => $fetchCommentsList, 'pathToPublic' => $this->path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    // Change le statut d'un commentaire en 'published'
    public function publishComment($data)
    {
        $id = $data;

        $publishCommentQuery = 'UPDATE comments SET status = "published" WHERE id = :id;';
        $publishComment = $this->pdo->prepare($publishCommentQuery);
        $publishComment->execute(['id' => $id]);
        header('Location: ' . $this->path . $this->adminLink . '/commentaires');
    }

    // Change le statut d'un commentaire en 'rejected'
    public function rejectComment($data)
    {
        $id = $data;

        $rejectCommentQuery = 'UPDATE comments SET status = "rejected" WHERE id = :id;';
        $rejectComment = $this->pdo->prepare($rejectCommentQuery);
        $rejectComment->execute(['id' => $id]);
        header('Location: ' . $this->path . $this->adminLink . '/commentaires');
    }

    // Supprime un commentaire
    public function deleteComment($data)
    {
        $id = $data;

        $deleteCommentQuery = 'DELETE from comments WHERE id = :id;';
        $deleteComment = $this->pdo->prepare($deleteCommentQuery);
        $deleteComment->execute(['id' => $id]);
        header('Location: ' . $this->path . $this->adminLink . '/commentaires');
    }
}
