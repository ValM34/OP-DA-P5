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
            $getPostListQuery = 'SELECT * FROM blog_posts;';
            $getPostList = $pdo->prepare($getPostListQuery);
            $getPostList->execute();
            $fetchPostList = $getPostList->fetchAll();
            $pathToPublic = new Helpers();
            $path = $pathToPublic->pathToPublic($numberOfPaths);
            include_once(__DIR__ . '/../templates/configTwig.php');
            if (isset($_SESSION['logged'])) {
                $_SESSION['role'] === 'admin' ? $this->isAdmin($twig, $fetchPostList, $path) : $this->isUser($twig, $path);
            } else {
                $this->isUser($twig, $path);
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

            if ($_SESSION['role'] === 'admin') {
                $hidePostQuery = 'DELETE FROM blog_posts WHERE id = :id;';
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

    function isAdmin($twig, $fetchPostList, $path)
    {
        $twig->display('adminPostList.twig', ['postList' => $fetchPostList, 'pathToPublic' => $path]);
    }
    function isUser($twig, $path)
    {
        $twig->display('home.twig', ['pathToPublic' => $path]);
    }
}

