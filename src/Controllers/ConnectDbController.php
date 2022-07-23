<?php

namespace Controllers;

use Models\ConnectDb;

echo 'début controller';
class ConnectDbController
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
            echo 'Connecté à la bdd !';
            $getPostListQuery = 'SELECT * FROM blog_posts;';
            $getPostList = $pdo->prepare($getPostListQuery);
            $getPostList->execute();
            $fetchPostList = $getPostList->fetchAll();
            echo var_dump($fetchPostList);
            $allow_cache = false;
            if ($allow_cache == true) {
                $cache = ['cache' => __DIR__ . '/tmp'];
            } else {
                $cache = [];
            }

            $loader = new \Twig\Loader\FilesystemLoader(__DIR__);
            $twig = new \Twig\Environment($loader, $cache);
            echo __DIR__;
            $template = $twig->load('templates/base.html.twig');
            echo $twig->render('postList.twig', ['postList' => $fetchPostList]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
    }
}
echo 'fin controller';
