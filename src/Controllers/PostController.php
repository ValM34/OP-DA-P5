<?php

namespace Controllers;

use Models\ConnectDb;
use Router\Helpers;

class PostController
{
    function display($numberOfPaths, $id_post)
    {
        $connectDb = new ConnectDb();
        $user = $connectDb->getUser();
        $password = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();

        try {
            $pdo = new \PDO($dataSourceName, $user, $password, $options);
            $getPostQuery = 'SELECT * FROM blog_posts WHERE id = :id;';
            $getPost = $pdo->prepare($getPostQuery);
            $getPost->execute(['id' => $id_post]);
            $fetchPost = $getPost->fetchAll();


            $getCommentsQuery = '
                SELECT J.id, J.id_post, J.id_user, J.content, J.created_at, J.updated_at, P.name, P.surname
                FROM comments J 
                JOIN users P
                ON J.id_user = P.id
                WHERE id_post = :id_post
                ORDER BY created_at;
            ';
            $getComments = $pdo->prepare($getCommentsQuery);
            $getComments->execute([
                'id_post' => $id_post
            ]);
            $fetchComments = $getComments->fetchAll();

            // Je vérifie si la personne est connectée pour lui afficher un message ou un autre au niveau des commentaires 
            $isLogged = false;
            if(!empty($_SESSION['id'])){
                $isLogged = true;
            }
            // -------------------------------------------------
            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);
            include_once(__DIR__ . '/../templates/configTwig.php');
            $twig->display('post.twig', [
                'post' => $fetchPost[0], 
                'comments' => $fetchComments, 
                'pathToPublic' => $path,
                'isLogged' => $isLogged    
            ]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    function add($numberOfPaths, $id_post_owner)
    {
        $connectDb = new ConnectDb();
        $user = $connectDb->getUser();
        $password = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();
        $id = $id_post_owner;

        try {
            $pdo = new \PDO($dataSourceName, $user, $password, $options);
            $getPostQuery = 'SELECT * FROM blog_posts WHERE id = :id;';
            $getPost = $pdo->prepare($getPostQuery);
            $getPost->execute(['id' => $id]);
            $fetchPost = $getPost->fetchAll();


            $getCommentsQuery = '
                SELECT J.id, J.id_post, J.id_user, J.content, J.created_at, J.updated_at, P.name, P.surname
                FROM comments J 
                JOIN users P
                ON J.id_user = P.id
                WHERE id_post = :id_post
                ORDER BY created_at;
            ';
            $getComments = $pdo->prepare($getCommentsQuery);
            $getComments->execute([
                'id_post' => $id
            ]);
            $fetchComments = $getComments->fetchAll();

            // A refaire plus proprement
            $id_user = $_SESSION['id'];
            $id_post = $id;
            $content = $_POST['content'];
            
            // ------------------------------
            $getAddPostQuery = 'INSERT INTO comments (id_post, id_user, content) VALUES (:id_post, :id_user, :content);';
            $getAddComment = $pdo->prepare($getAddPostQuery);
            $getAddComment->execute([
                'id_post' => $id_post,
                'id_user' => $id_user,
                'content' => $content
            ]);

            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);
            // Je laisse header.location car visiblement il permet au nouveau commentaire d'être affiché contrairement au display de twig.
            header('Location: http://op-da-p5/public/post/'.$id_post);
            // include_once(__DIR__ . '/../templates/configTwig.php');
            // $twig->display('post.twig', ['post' => $fetchPost[0], 'comments' => $fetchComments, 'pathToPublic' => $path]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }
}
