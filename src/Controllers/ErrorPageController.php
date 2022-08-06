<?php

namespace Controllers;

use Router\Helpers;

class ErrorPageController
{
    public function display($numberOfPaths, $isLogged)
    {
        include('../src/templates/configTwig.php');
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        
        if(1 === $isLogged) {
            $twig->display('errorPage.twig', ['pathToPublic' => $path, 'isLogged' => $isLogged]);
        } else {
            $twig->display('errorPage.twig', ['pathToPublic' => $path]);
        }
    }
}
