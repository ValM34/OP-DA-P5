<?php

namespace Controllers;

use Router\Helpers;
use Models\ConnectDb;

class AdminPostListController
{
    function display($numberOfPaths)
    {
        // Ici je vais afficher la liste de posts
        // Un bouton pour supprimer et un bouton pour dépublier les articles
        // Un lien vers les articles pour aller les modifier.
        // Un lien vers la page pour créer un article
        $connectDb = new ConnectDb();
        $user = $connectDb->getUser();
        $password = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();
        // $pdo = $connectDb->getPdo();

        // $dataSourceName = "mysql:host=$host;dbname=$database;port=$port;charset=$charset";
        // mysql:host=localhost;dbname=blogp5;port=3306;charset=UTF8

        try {
            $pdo = new \PDO($dataSourceName, $user, $password, $options);
            $getPostListQuery = 'SELECT * FROM blog_posts ORDER BY created_at desc;';
            $getPostList = $pdo->prepare($getPostListQuery);
            $getPostList->execute();
            $fetchPostList = $getPostList->fetchAll();
            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);
            include_once(__DIR__ . '/../templates/configTwig.php');
            if (isset($_SESSION['logged'])) {
                $_SESSION['role'] === 'admin' ? $this->isAdmin($twig, $fetchPostList, $path, 'adminPostList.twig') : $this->isUser($twig, $path, 'home.twig');
            } else {
                $this->isUser($twig, $path, 'home.twig');
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    function hide($numberOfPaths, $id_post)
    {
        $connectDb = new ConnectDb();
        $user = $connectDb->getUser();
        $password = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();

        try {
            $pdo = new \PDO($dataSourceName, $user, $password, $options);
            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);

            if ($_SESSION['role'] === 'admin') {
                $hidePostQuery = 'UPDATE blog_posts SET status = "hidden" WHERE id = :id;';
                $hidePost = $pdo->prepare($hidePostQuery);
                $hidePost->execute(['id' => $id_post]);
                header('Location: ' . $path . 'admin-15645/touslesarticles');
            } else {
                header('Location: ' . $path . 'accueil');
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    function publish($numberOfPaths, $id_post)
    {
        $connectDb = new ConnectDb();
        $user = $connectDb->getUser();
        $password = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();

        try {
            $pdo = new \PDO($dataSourceName, $user, $password, $options);
            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);

            if ($_SESSION['role'] === 'admin') {
                $hidePostQuery = 'UPDATE blog_posts SET status = "published" WHERE id = :id;';
                $hidePost = $pdo->prepare($hidePostQuery);
                $hidePost->execute(['id' => $id_post]);
                header('Location: ' . $path . 'admin-15645/touslesarticles');
            } else {
                header('Location: ' . $path . 'accueil');
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    function delete($numberOfPaths, $id_post)
    {
        $connectDb = new ConnectDb();
        $user = $connectDb->getUser();
        $password = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();

        try {
            $pdo = new \PDO($dataSourceName, $user, $password, $options);
            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);

            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] === 'admin') {
                    $hidePostQuery = 'DELETE FROM blog_posts WHERE id = :id;';
                    $hidePost = $pdo->prepare($hidePostQuery);
                    $hidePost->execute(['id' => $id_post]);
                    header('Location: ' . $path . 'admin-15645/touslesarticles');
                } else {
                    header('Location: ' . $path . 'accueil');
                }
            } else {
                header('Location: ' . $path . 'accueil');
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    function displayAddPostPage($numberOfPaths)
    {
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        include_once(__DIR__ . '/../templates/configTwig.php');
        if (isset($_SESSION['logged'])) {
            $_SESSION['role'] === 'admin' ? $this->isAdmin($twig, null, $path, 'adminAddPost.twig') : $this->isUser($twig, $path, 'home.twig');
        } else {
            $this->isUser($twig, $path, 'home.twig');
        }
    }

    function addPost($numberOfPaths)
    {
        $connectDb = new ConnectDb();
        $user = $connectDb->getUser();
        $password = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();

        try {
            $pdo = new \PDO($dataSourceName, $user, $password, $options);
            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);
            if ($_SESSION['role'] === 'admin') {
                $id = '66';
                // A corriger en validant d'abord les données envoyées par l'utilisateur
                $title = $_POST['postTitle'];
                $content = $_POST['postContent'];
                echo $id . '<br>';
                echo $title . '<br>';
                echo $content . '<br>';

                $addPostQuery = 'INSERT INTO blog_posts (idUser, title, content) VALUES (:idUser, :title, :content);';
                $addPost = $pdo->prepare($addPostQuery);
                $addPost->execute([
                    'idUser' => $id,
                    'title' => $title,
                    'content' => $content
                ]);
                header('Location: ' . $path . 'admin-15645/touslesarticles');
            } else {
                header('Location: ' . $path . 'accueil');
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    function displayUpdatePostPage($numberOfPaths, $id_post)
    {
        $connectDb = new ConnectDb();
        $user = $connectDb->getUser();
        $password = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();

        try {
            $pdo = new \PDO($dataSourceName, $user, $password, $options);
            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);

            $displayUpdatePostQuery = 'SELECT * FROM blog_posts WHERE id = :id;';
            $displayUpdatePost = $pdo->prepare($displayUpdatePostQuery);
            $displayUpdatePost->execute(['id' => $id_post]);
            $fetchPost = $displayUpdatePost->fetchAll();
            var_dump($fetchPost);
            include_once(__DIR__ . '/../templates/configTwig.php');

            if (isset($_SESSION['role'])) {
                $_SESSION['role'] === 'admin' ? $this->isAdmin($twig, $fetchPost[0], $path, 'adminUpdatePostPage.twig') : $this->isUser($twig, $path, 'home.twig');
            }

        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    function updatePost($numberOfPaths, $id_post)
    {
        $connectDb = new ConnectDb();
        $user = $connectDb->getUser();
        $password = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();

        try {
            $pdo = new \PDO($dataSourceName, $user, $password, $options);
            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);

            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] === 'admin') {

                    // corriger pour ensuite valider les données de l'utilisateur avant de les utiliser
                    $title = $_POST['title'];
                    $content = $_POST['content'];

                    $updatePostQuery = 'UPDATE blog_posts SET title = :title, content = :content, updated_at = CURRENT_TIMESTAMP WHERE id = :id;';
                    $updatePost = $pdo->prepare($updatePostQuery);
                    $updatePost->execute([
                        'id' => $id_post,
                        'title' => $title,
                        'content' => $content
                    ]);
                    header('Location: ' . $path . 'admin-15645/article/' . $id_post . '/afficher'); // Envoyer vers la page de vue de l'article
                } else {
                    header('Location: ' . $path . 'accueil');
                }
            } else {
                header('Location: ' . $path . 'accueil');
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }

    function isAdmin($twig, $fetchPostList, $path, $twigPage)
    {
        if (null === $fetchPostList) {
            $twig->display($twigPage, ['pathToPublic' => $path]);
        } else {
            $twig->display($twigPage, ['postList' => $fetchPostList, 'pathToPublic' => $path]);
        }
    }
    function isUser($twig, $path, $twigPage)
    {
        $twig->display('home.twig', ['pathToPublic' => $path]);
    }
}
