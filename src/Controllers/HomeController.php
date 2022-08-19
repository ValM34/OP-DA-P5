<?php

namespace Controllers;

use Router\Helpers;
use Models\Contact;

class HomeController
{
    public function __construct()
    {
        $this->helpers = new Helpers();
    }

    public function display($numberOfPaths)
    {
        include('../src/templates/configTwig.php');
        $message = '';
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();
        $twig->display('home.twig', ['message' => $message, 'pathToPublic' => $path, 'userSession' => $userSession]);
    }

    public function sendMail()
    {
        $contact = new Contact();

        $contact->setSujet(htmlspecialchars($_POST['sujet']));
        $contact->setCorp(htmlspecialchars($_POST['corp']));
        $contact->setName(htmlspecialchars($_POST['name']));
        $contact->setLastName(htmlspecialchars($_POST['lastName']));
        $contact->setEmail(htmlspecialchars($_POST['email']));

        $dest = $contact->getDest();
        $headers = $contact->getHeaders();
        $sujet = $contact->getTitle();
        $corp = $contact->getCorp();

        $contact->sendEmail($dest, $sujet, $corp, $headers);
        $message = urlencode("Votre email a bien été envoyé");
        header('Location: http://localhost/op-da-p5/public/accueil?message=' . $message);
    }
}
