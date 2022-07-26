<?php

namespace Controllers;

class Home
{
    public function displayHome()
    {
        include('../src/templates/configTwig.php');
        $message = '';
        if(isset($_GET['message'])){
            $message = $_GET['message'];
        }
        $twig->display('home.twig', ['message' => $message]);
    }
}