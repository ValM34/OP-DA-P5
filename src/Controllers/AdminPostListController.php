<?php

namespace Controllers;

use Router\Helpers;
use Models\ConnectDb;

class AdminPostListController
{
    function display($numberOfPaths)
    {
        // Ici je vais afficher la list de posts
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
            $twig->display('adminPostList.twig', ['postList' => $fetchPostList, 'pathToPublic' => $path]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }
}
