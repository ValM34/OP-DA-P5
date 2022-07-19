<?php

class Contact 
{
    private $sujet;
    private $corp;
    private $name;
    private $lastName;
    private $email;

    private static $dest = "valentin.moreau34750@gmail.com";
    private static $contactTitle = 'Blog-Valentin: ';
    private static $headers = 'From: valentin.moreau34750@gmail.com';

    public function getDest()
    {
        return Contact::$dest;
    }
    public function getContactTitle()
    {
        return $this->contactTitle;
    }
    public function getHeaders()
    {
        return Contact::$headers;
    }
    public function getTitle()
    {
        return Contact::$contactTitle . $this->name . " " . $this->lastName . " (" . $this->email . ") vous a envoyé: " . $this->sujet;
    }
    public function getCorp()
    {
        return $this->corp;
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

    public function sendEmail($dest, $sujet, $corp, $headers)
    {
        if (mail($dest, $sujet, $corp, $headers)) {
            echo "Email envoyé avec succès à" . $dest . "...";
        } else {
            echo "Échec de l'envoi de l'email...";
        }
    }

}