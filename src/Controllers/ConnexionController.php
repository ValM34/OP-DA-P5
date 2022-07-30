<?php

namespace Controllers;

use Router\Helpers;

class ConnexionController
{
    public function display($numberOfPaths)
    {
        include('../src/templates/configTwig.php');
        $message = '';
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        $twig->display('connexion.twig', ['message' => $message, 'pathToPublic' => $path]);
    }

    public function connexion()
    {
        
    }
}
