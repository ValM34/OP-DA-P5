<?php

namespace Controllers\Suscribe;

use Router\PathToPublic;

class SuscribeControllerRead
{
    public function setSuscribePage($nombreDeMots)
    {
        include('../src/templates/configTwig.php');
        $pathToPublic = new PathToPublic();
        $path = $pathToPublic->link($nombreDeMots);
        $twig->display('suscribe.twig', ['pathToPublic' => $path]);
    }
}
