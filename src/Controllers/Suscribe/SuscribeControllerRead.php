<?php

namespace Controllers\Suscribe;

class SuscribeControllerRead
{
    public function setSuscribePage()
    {
        include_once(__DIR__ . '/../../templates/configTwig.php');
        echo $twig->render('suscribe.twig');
    }
}
