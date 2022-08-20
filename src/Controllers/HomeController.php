<?php

namespace Controllers;

use Router\Helpers;
use Models\Contact;

class HomeController
{
    public function __construct()
    {
        $this->helpers = new Helpers();
        $this->path = $this->helpers->pathToPublic();
    }

    // Affiche la page d'accueil
    public function display()
    {
        include('../src/templates/configTwig.php');
        $message = '';
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        $userSession = $this->helpers->isLogged();
        $twig->display('home.twig', ['message' => $message, 'pathToPublic' => $this->path, 'userSession' => $userSession]);
    }

    // Envoie un email au propriétaire du site
    public function sendMail()
    {
        $contact = new Contact();

        $contact->setSujet(htmlspecialchars($_POST['sujet']));
        $contact->setCorp(htmlspecialchars($_POST['corp']));
        $contact->setName(htmlspecialchars($_POST['name']));
        $contact->setLastName(htmlspecialchars($_POST['lastName']));
        $contact->setEmail(htmlspecialchars($_POST['email']));
        $contact->setDest($_ENV['contactEmail']);
        $contact->setHeaders('From: ' . $_ENV['contactEmail']);

        $dest = $contact->getDest();
        $headers = $contact->getHeaders();
        echo $dest . ' / ' . $headers;
        $headers = $contact->getHeaders();
        $sujet = $contact->getTitle();
        $corp = $contact->getCorp();

        $contact->sendEmail($dest, $sujet, $corp, $headers);
        $message = urlencode('Votre email a bien été envoyé');
        header('Location: http://localhost/op-da-p5/public/accueil?message=' . $message);
    }
}
