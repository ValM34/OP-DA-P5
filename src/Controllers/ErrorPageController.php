<?php

namespace Controllers;

use Router\Helpers;

class ErrorPageController
{
    public function __construct()
    {
        $this->helpers = new Helpers();
    }

    public function display($numberOfPaths)
    {
        include('../src/templates/configTwig.php');
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();

        if (true === $userSession['logged']) {
            $twig->display('errorPage.twig', ['pathToPublic' => $path, 'userSession' => $userSession]);
        } else {
            $twig->display('errorPage.twig', ['pathToPublic' => $path]);
        }
    }
}
