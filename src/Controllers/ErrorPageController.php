<?php

namespace Controllers;

use Router\Helpers;

class ErrorPageController
{
  public function __construct()
  {
    $this->helpers = new Helpers();
    $this->path = $this->helpers->pathToPublic();
  }

  // Affiche la page d'erreur
  public function display()
  {
    include '../src/templates/configTwig.php';
    $userSession = $this->helpers->isLogged();

    if (true === $userSession['logged']) {
      $twig->display('errorPage.twig', ['pathToPublic' => $this->path, 'userSession' => $userSession]);
    } else {
      $twig->display('errorPage.twig', ['pathToPublic' => $this->path]);
    }
  }
}
