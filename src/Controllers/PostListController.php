<?php

namespace Controllers;

use Models\ConnectDb;

class PostListController
{
    function setDbConnexion()
    {
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
            include_once(__DIR__ . '/../templates/configTwig.php');
            echo $twig->render('postList.twig', ['postList' => $fetchPostList]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }
}
