<?php

namespace Controllers;

use Models\ConnectDb;
use Router\Helpers;

class PostController
{
    public function __construct()
    {
        $connectDb = new ConnectDb();
        $this->pdo = $connectDb->connect();
        $this->helpers = new Helpers();
    }

    public function displayPostList($numberOfPaths, $userSession)
    {
        $getPostListQuery = 'SELECT * FROM blog_posts;';
        $getPostList = $this->pdo->prepare($getPostListQuery);
        $getPostList->execute();
        $fetchPostList = $getPostList->fetchAll();
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        $fetchPostList = $this->helpers->dateConverter($fetchPostList);
        include_once(__DIR__ . '/../templates/configTwig.php');
        if (true === $userSession['logged']) {
            $twig->display('postList.twig', ['postList' => $fetchPostList, 'pathToPublic' => $path, 'userSession' => $userSession]);
        } else {
            $twig->display('postList.twig', ['postList' => $fetchPostList, 'pathToPublic' => $path]);
        }
    }

    public function displayPost($numberOfPaths, $id_post, $userSession)
    {
        $getPostQuery = 'SELECT * FROM blog_posts WHERE id = :id;';
        $getPost = $this->pdo->prepare($getPostQuery);
        $getPost->execute(['id' => $id_post]);
        $fetchPost = $getPost->fetchAll();

        $getCommentsQuery = '
                SELECT J.id, J.id_post, J.id_user, J.content, J.status, J.created_at, J.updated_at, P.name, P.surname
                FROM comments J 
                JOIN users P
                ON J.id_user = P.id
                WHERE id_post = :id_post
                ORDER BY created_at;
            ';
        $getComments = $this->pdo->prepare($getCommentsQuery);
        $getComments->execute([
            'id_post' => $id_post
        ]);
        $fetchComments = $getComments->fetchAll();

        $path = $this->helpers->pathToPublic($numberOfPaths);
        $fetchPost = $this->helpers->dateConverter($fetchPost);
        $fetchComments = $this->helpers->dateConverter($fetchComments);
        include_once(__DIR__ . '/../templates/configTwig.php');
        $twig->display('post.twig', [
            'post' => $fetchPost[0],
            'comments' => $fetchComments,
            'pathToPublic' => $path,
            'userSession' => $userSession
        ]);
    }

    public function addComment($id_post_owner, $userSession)
    {
        $id = $id_post_owner;
        if (true === $userSession['logged']) {
            $getPostQuery = 'SELECT * FROM blog_posts WHERE id = :id;';
            $getPost = $this->pdo->prepare($getPostQuery);
            $getPost->execute(['id' => $id]);

            $getCommentsQuery = '
                SELECT J.id, J.id_post, J.id_user, J.content, J.created_at, J.updated_at, P.name, P.surname
                FROM comments J 
                JOIN users P
                ON J.id_user = P.id
                WHERE id_post = :id_post
                ORDER BY created_at;
            ';
            $getComments = $this->pdo->prepare($getCommentsQuery);
            $getComments->execute([
                'id_post' => $id
            ]);

            $id_user = $_SESSION['user']['id'];
            $id_post = $id;
            $content = htmlspecialchars($_POST['content']);

            $getAddPostQuery = 'INSERT INTO comments (id_post, id_user, content) VALUES (:id_post, :id_user, :content);';
            $getAddComment = $this->pdo->prepare($getAddPostQuery);
            $getAddComment->execute([
                'id_post' => $id_post,
                'id_user' => $id_user,
                'content' => $content
            ]);
            header('Location: http://localhost/op-da-p5/public/post/' . $id_post_owner);
        } else {
            header('Location: http://localhost/op-da-p5/public/post/' . $id_post_owner);
        }
    }
}
