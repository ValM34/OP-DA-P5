<?php

namespace App\Controllers;

class Home
{
    public function displayHome()
    {
        include('../templates/configTwig.php');
        $message = '';
        if(isset($_GET['message'])){
            $message = $_GET['message'];
        }
        $twig->display('home.twig', ['message' => $message]);
    }
}
