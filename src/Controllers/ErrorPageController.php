<?php

namespace Controllers;

use Router\Helpers;

class ErrorPageController
{
    public function display($numberOfPaths)
    {
        include('../src/templates/configTwig.php');
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        $twig->display('errorPage.twig', ['pathToPublic' => $path]);
    }
}
