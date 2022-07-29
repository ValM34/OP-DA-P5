<?php

namespace Controllers\Connexion;

class ConnexionControllerRead
{
    public function displayConnexionControllerRead($nombreDeMots)
    {
        include('../src/templates/configTwig.php');
        $message = '';
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        $pathToPublic = new PathToPublic();
        $path = $pathToPublic->link($nombreDeMots);
        $twig->display('connexion.twig', ['message' => $message, 'pathToPublic' => $path]);
    }
}