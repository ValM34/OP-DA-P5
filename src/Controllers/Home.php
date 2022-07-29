<?php

namespace Controllers;

use Router\PathToPublic;

class Home
{
    public function displayHome($nombreDeMots)
    {
        include('../src/templates/configTwig.php');
        $message = '';
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        $pathToPublic = new PathToPublic();
        $path = $pathToPublic->link($nombreDeMots);
        $twig->display('home.twig', ['message' => $message, 'pathToPublic' => $path]);
    }
}



        /*$link = '';
        $link2 = $link;
        for ($i = 1; $i < $nombreDeMots; $i++) {
            $link2 = sprintf("%s%s", $link, '../');
            $link = $link2;
        }
        $link = $link2;
        echo $link;*/