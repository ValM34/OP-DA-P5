<?php

namespace Controllers;

use Router\Helpers;
use Models\Contact;
use Globals\Globals;

class HomeController
{
    public function __construct()
    {
        $this->helpers = new Helpers();
        $this->path = $this->helpers->pathToPublic();
        $this->globals = new Globals();
    }

    // Affiche la page d'accueil
    public function display($message)
    {
        include_once '../src/templates/configTwig.php';
        $userSession = $this->helpers->isLogged();
        $twig->display('home.twig', ['message' => $message, 'pathToPublic' => $this->path, 'userSession' => $userSession]);
    }

    // Envoie un email au propriétaire du site
    public function sendMail()
    {
        $contact = new Contact();

        $post['sujet'] = htmlspecialchars($this->globals->getPOST('sujet'));
        $post['corp'] = htmlspecialchars($this->globals->getPOST('corp'));
        $post['name'] = htmlspecialchars($this->globals->getPOST('name'));
        $post['lastName'] = htmlspecialchars($this->globals->getPOST('lastName'));
        $post['email'] = htmlspecialchars($this->globals->getPOST('email'));
        $contact->setSujet($post['sujet']);
        $contact->setCorp($post['corp']);
        $contact->setName($post['name']);
        $contact->setLastName($post['lastName']);
        $contact->setEmail($post['email']);
        
        $contact->setDest($this->globals->getENV('contactEmail'));
        $contact->setHeaders('From: ' . $this->globals->getENV('contactEmail'));

        $dest = $contact->getDest();
        $headers = $contact->getHeaders();
        $headers = $contact->getHeaders();
        $sujet = $contact->getTitle();
        $corp = $contact->getCorp();

        $contact->sendEmail($dest, $sujet, $corp, $headers);
        $message = urlencode('Votre email a bien été envoyé');
				include_once '../src/templates/configTwig.php';
        $twig->display('home.twig', ['message' => $message, 'pathToPublic' => $this->path]);
    }
}
