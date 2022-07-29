<?php

namespace Controllers;

// include_once('../../models/Contact.php');



use Models\Contact;

class ContactController
{

    public function displayContainer()
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
echo 'PAGE Contact';

?>