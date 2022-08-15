<?php
/*
namespace Controllers;

use Models\ConnectDb;
use Router\Helpers;

class PostListController
{
    public function __construct()
    {
        $connectDb = new ConnectDb();
        $this->pdo = $connectDb->connect();
        $this->helpers = new Helpers();
    }

    function display($numberOfPaths, $isLogged)
    {
        $getPostListQuery = 'SELECT * FROM blog_posts;';
        $getPostList = $this->pdo->prepare($getPostListQuery);
        $getPostList->execute();
        $fetchPostList = $getPostList->fetchAll();
        $path = $this->helpers->pathToPublic($numberOfPaths);
        include_once(__DIR__ . '/../templates/configTwig.php');
        if (true === $isLogged) {
            $twig->display('postList.twig', ['postList' => $fetchPostList, 'pathToPublic' => $path, 'isLogged' => $isLogged]);
        } else {
            $twig->display('postList.twig', ['postList' => $fetchPostList, 'pathToPublic' => $path]);
        }
    }
}
*/