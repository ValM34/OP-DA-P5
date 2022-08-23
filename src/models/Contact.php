<?php

namespace Models;

class Contact 
{
    private $sujet;
    private $corp;
    private $name;
    private $lastName;
    private $email;
    private $dest;
    private $headers;

    private static $contactTitle = 'Blog-Valentin: ';

    public function getTitle()
    {
        return Contact::$contactTitle . $this->name . ' ' . $this->lastName . ' (' . $this->email . ') vous a envoyé: ' . $this->sujet;
    }
    public function getCorp()
    {
        return $this->corp;
    }
    public function getDest()
    {
        return $this->dest;
    }
    public function getHeaders()
    {
        return $this->headers;
    }

    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    }
    public function setCorp($corp)
    {
        $this->corp = $corp;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setlastName($lastName)
    {
        $this->lastName = $lastName;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setDest($dest)
    {
        $this->dest = $dest;
    }
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    // Envoie le message de succès ou d'erreur à l'issue de l'envoi d'un mail
    public function sendEmail($dest, $sujet, $corp, $headers)
    {
        mail($dest, $sujet, $corp, $headers);
    }

}