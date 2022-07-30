<?php

namespace Controllers;

use Router\Helpers;
use Models\Contact;

class HomeController
{
    public function display($numberOfPaths)
    {
        include('../src/templates/configTwig.php');
        $message = '';
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        $twig->display('home.twig', ['message' => $message, 'pathToPublic' => $path]);
    }

    public function sendMail()
    {
        $contact = new Contact ();

        $contact->setSujet($_POST['sujet']);
        $contact->setCorp($_POST['corp']);
        $contact->setName($_POST['name']);
        $contact->setLastName($_POST['lastName']);
        $contact->setEmail($_POST['email']);
        
        $dest = $contact->getDest();
        $headers = $contact->getHeaders();
        $sujet = $contact->getTitle();
        $corp = $contact->getCorp();
        
        $contact->sendEmail($dest, $sujet, $corp, $headers);
        $message = urlencode("Votre email a bien été envoyé");
        header('Location: http://op-da-p5/public/accueil?message='.$message);
    }
}
