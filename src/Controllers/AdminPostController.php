<?php

namespace Controllers;

use Router\Helpers;
use Models\ConnectDb;

class AdminPostController
{
    private $pdo;
    private $helpers;
    private $adminLink;

    public function __construct($numberOfPaths)
    {
        $connectDb = new ConnectDb();
        $this->pdo = $connectDb->connect();
        $this->helpers = new Helpers();
        $this->helpers->isAdmin($numberOfPaths);
        $this->adminLink = $_ENV['adminLink'];
        $this->path = $this->helpers->pathToPublic($numberOfPaths);
    }

    public function displayHomePage($numberOfPaths)
    {
        include_once(__DIR__ . '/../templates/configTwig.php');
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();
        $twig->display('adminHomePage.twig', ['pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    public function display($numberOfPaths)
    {
        $getPostListQuery = 'SELECT * FROM blog_posts ORDER BY created_at desc;';
        $getPostList = $this->pdo->prepare($getPostListQuery);
        $getPostList->execute();
        $fetchPostList = $getPostList->fetchAll();
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();
        include_once(__DIR__ . '/../templates/configTwig.php');
        $fetchPostList = $this->helpers->dateConverter($fetchPostList);
        $twig->display('adminPostList.twig', ['postList' => $fetchPostList, 'pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    public function hide($numberOfPaths, $id_post)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);

        if ($_SESSION['user']['role'] === 'admin') {
            $hidePostQuery = 'UPDATE blog_posts SET status = "hidden" WHERE id = :id;';
            $hidePost = $this->pdo->prepare($hidePostQuery);
            $hidePost->execute(['id' => $id_post]);
            header('Location: ' . $path . $this->adminLink . '/touslesarticles');
        } else {
            header('Location: ' . $path . 'accueil');
        }
    }

    public function publish($numberOfPaths, $id_post)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);

        if ($_SESSION['user']['role'] === 'admin') {
            $publishPostQuery = 'UPDATE blog_posts SET status = "published" WHERE id = :id;';
            $publishPost = $this->pdo->prepare($publishPostQuery);
            $publishPost->execute(['id' => $id_post]);
            header('Location: ' . $path . $this->adminLink . '/touslesarticles');
        } else {
            header('Location: ' . $path . 'accueil');
        }
    }

    public function delete($numberOfPaths, $id_post)
    {
        $getImgSrcQuery = 'SELECT img_src FROM blog_posts WHERE id = :id;';
        $getImgSrc = $this->pdo->prepare($getImgSrcQuery);
        $getImgSrc->execute(['id' => $id_post]);
        $fetchImgSrc = $getImgSrc->fetchAll();

        if ($fetchImgSrc[0]['img_src'] !== null) {
            $pathToDeleteImg = __DIR__ . "/../../public/". $fetchImgSrc[0]['img_src'];
            unlink($pathToDeleteImg);
        }

        $hidePostQuery = 'DELETE FROM blog_posts WHERE id = :id;';
        $hidePost = $this->pdo->prepare($hidePostQuery);
        $hidePost->execute(['id' => $id_post]);
        header('Location: ' . $this->path . $this->adminLink . '/touslesarticles');
    }

    public function displayAddPostPage($numberOfPaths)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();
        include_once(__DIR__ . '/../templates/configTwig.php');
        $twig->display('adminAddPost.twig', ['pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    public function addPost($numberOfPaths)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();
        if (!isset($_POST['postTitle'])) {
            header('Location: ' . $path . $this->adminLink . '/touslesarticles');
        }
        $id = $_SESSION['user']['id'];
        $title = htmlspecialchars($_POST['postTitle']);
        $content = htmlspecialchars($_POST['postContent']);
        $chapo = htmlspecialchars($_POST['postChapo']);

        if (empty($title) || empty($content) || empty($chapo)) {
            include_once(__DIR__ . '/../templates/configTwig.php');
            $twig->display('adminAddPost.twig', ['pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink, 'errorMsg' => true]);
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
            $fileName = "C:\wamp64\www\OP-DA-P5\public\images/" . $uniqueName . $fileExt;
            if ($_FILES['postFile']['error'] || $fileSize > $maxSize || !in_array($fileExt, $validExt)) {
                include_once(__DIR__ . '/../templates/configTwig.php');
                $twig->display('adminAddPost.twig', ['pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink, 'errorMsg' => true]);
                return;
            }
            move_uploaded_file($tmpName, $fileName);

            $src = "images/" . $uniqueName . $fileExt;


            $addPostQuery = 'INSERT INTO blog_posts (idUser, title, content, chapo, img_src) VALUES (:idUser, :title, :content, :chapo, :img_src);';
            $addPost = $this->pdo->prepare($addPostQuery);
            $addPost->execute([
                'idUser' => $id,
                'title' => $title,
                'content' => $content,
                'chapo' => $chapo,
                'img_src' => $src
            ]);
            header('Location: ' . $path . $this->adminLink . '/touslesarticles');
        } else {
            $addPostQuery = 'INSERT INTO blog_posts (idUser, title, content, chapo) VALUES (:idUser, :title, :content, :chapo);';
            $addPost = $this->pdo->prepare($addPostQuery);
            $addPost->execute([
                'idUser' => $id,
                'title' => $title,
                'content' => $content,
                'chapo' => $chapo
            ]);
            // header('Location: ' . $path . $this->adminLink . '/touslesarticles');
        }
    }

    public function displayUpdatePostPage($numberOfPaths, $id_post)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();

        $displayUpdatePostQuery = 'SELECT * FROM blog_posts WHERE id = :id;';
        $displayUpdatePost = $this->pdo->prepare($displayUpdatePostQuery);
        $displayUpdatePost->execute(['id' => $id_post]);
        $fetchPost = $displayUpdatePost->fetchAll();

        include_once(__DIR__ . '/../templates/configTwig.php');
        $twig->display('adminUpdatePostPage.twig', ['postList' => $fetchPost[0], 'pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    public function updatePost($numberOfPaths, $id_post)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();
        if (!isset($_POST['title'])) {
            header('Location: ' . $path . $this->adminLink . '/touslesarticles');
        }
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $chapo = htmlspecialchars($_POST['chapo']);

        if (empty($title) || empty($content) || empty($chapo)) {
            include_once(__DIR__ . '/../templates/configTwig.php');
            $twig->display('adminAddPost.twig', ['pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink, 'errorMsg' => true]);
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
            $fileName = "C:\wamp64\www\OP-DA-P5\public\images/" . $uniqueName . $fileExt;
            if ($_FILES['postFile']['error'] || $fileSize > $maxSize || !in_array($fileExt, $validExt)) {
                include_once(__DIR__ . '/../templates/configTwig.php');
                $twig->display('adminUpdatePostPage.twig', ['pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink, 'errorMsg' => true]);
                return;
            }
            $getImgSrcQuery = "SELECT img_src FROM blog_posts WHERE id = :id;";
            $getImgSrc = $this->pdo->prepare($getImgSrcQuery);
            $getImgSrc->execute(['id' => $id_post]);
            $fetchImgSrc = $getImgSrc->fetchAll();
            // Suppression de l'ancienne l'image
            if (($fetchImgSrc[0]['img_src'] === null) !== true) {
                $pathToDeleteImg = "C:\wamp64\www\OP-DA-P5\public/" . $fetchImgSrc[0]['img_src'];
                unlink($pathToDeleteImg);
            }




            move_uploaded_file($tmpName, $fileName);
            $src = "images/" . $uniqueName . $fileExt;

            $updatePostQuery = 'UPDATE blog_posts SET title = :title, content = :content, chapo = :chapo, img_src = :img_src, updated_at = CURRENT_TIMESTAMP WHERE id = :id;';
            $updatePost = $this->pdo->prepare($updatePostQuery);
            $updatePost->execute([
                'id' => $id_post,
                'title' => $title,
                'content' => $content,
                'chapo' => $chapo,
                'img_src' => $src
            ]);
            header('Location: ' . $path . $this->adminLink . '/article/' . $id_post . '/afficher'); // Envoyer vers la page de vue de l'article
        } else {
            $updatePostQuery = 'UPDATE blog_posts SET title = :title, content = :content, chapo = :chapo, updated_at = CURRENT_TIMESTAMP WHERE id = :id;';
            $updatePost = $this->pdo->prepare($updatePostQuery);
            $updatePost->execute([
                'id' => $id_post,
                'title' => $title,
                'content' => $content,
                'chapo' => $chapo
            ]);
            header('Location: ' . $path . $this->adminLink . '/article/' . $id_post . '/afficher'); // Envoyer vers la page de vue de l'article
        }
    }

    public function isUser($twig, $path)
    {
        $userSession = $this->helpers->isLogged();
        $twig->display('home.twig', ['pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    public function publishSelected($numberOfPaths, $idPostList)
    {
        $arrayIdPosts = explode("-", $idPostList);
        array_shift($arrayIdPosts);
        $publishSelectedQuery = "UPDATE blog_posts SET status = 'published' WHERE ID = :id_post;";
        $publishSelected = $this->pdo->prepare($publishSelectedQuery);
        foreach ($arrayIdPosts as $id_post) {
            $publishSelected->execute([
                "id_post" => $id_post
            ]);
        }
        $path = $this->helpers->pathToPublic($numberOfPaths);
        // $userSession = $this->helpers->isLogged();
        header('Location: ' . $path . $this->adminLink . '/touslesarticles');
    }

    public function hideSelected($numberOfPaths, $idPostList)
    {
        $arrayIdPosts = explode("-", $idPostList);
        array_shift($arrayIdPosts);
        $hideSelectedQuery = "UPDATE blog_posts SET status = 'hidden' WHERE ID = :id_post;";
        $hideSelected = $this->pdo->prepare($hideSelectedQuery);
        foreach ($arrayIdPosts as $id_post) {
            $hideSelected->execute([
                "id_post" => $id_post
            ]);
        }
        $path = $this->helpers->pathToPublic($numberOfPaths);
        // $userSession = $this->helpers->isLogged();
        header('Location: ' . $path . $this->adminLink . '/touslesarticles');
    }

    public function deleteSelected($numberOfPaths, $idPostList)
    {
        $arrayIdPosts = explode("-", $idPostList);
        array_shift($arrayIdPosts);
        $deleteSelectedQuery = "DELETE FROM blog_posts WHERE ID = :id_post;";
        $deleteSelected = $this->pdo->prepare($deleteSelectedQuery);
        foreach ($arrayIdPosts as $id_post) {
            $deleteSelected->execute([
                "id_post" => $id_post
            ]);
        }
        $path = $this->helpers->pathToPublic($numberOfPaths);
        // $userSession = $this->helpers->isLogged();
        header('Location: ' . $path . $this->adminLink . '/touslesarticles');
    }

    public function displayCommentsList($numberOfPaths)
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
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();
        include_once(__DIR__ . '/../templates/configTwig.php');
        $fetchCommentsList = $this->helpers->dateConverter($fetchCommentsList);
        $twig->display('adminCommentsList.twig', ['CommentsList' => $fetchCommentsList, 'pathToPublic' => $path, 'userSession' => $userSession, 'adminLink' => $this->adminLink]);
    }

    public function publishComment($numberOfPaths, $data)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $id = $data;

        $publishCommentQuery = 'UPDATE comments SET status = "published" WHERE id = :id;';
        $publishComment = $this->pdo->prepare($publishCommentQuery);
        $publishComment->execute(['id' => $id]);
        header('Location: ' . $path . $this->adminLink . '/commentaires');
    }

    public function rejectComment($numberOfPaths, $data)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $id = $data;

        $rejectCommentQuery = 'UPDATE comments SET status = "rejected" WHERE id = :id;';
        $rejectComment = $this->pdo->prepare($rejectCommentQuery);
        $rejectComment->execute(['id' => $id]);
        header('Location: ' . $path . $this->adminLink . '/commentaires');
    }

    public function deleteComment($numberOfPaths, $data)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $id = $data;

        $deleteCommentQuery = 'DELETE from comments WHERE id = :id;';
        $deleteComment = $this->pdo->prepare($deleteCommentQuery);
        $deleteComment->execute(['id' => $id]);
        header('Location: ' . $path . $this->adminLink . '/commentaires');
    }
}
