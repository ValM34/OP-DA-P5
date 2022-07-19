<?php

namespace App\Controllers;

class Home
{
    public function displayHome()
    {
        include('../templates/configTwig.php');
        $twig->display('home.twig');
    }
}
