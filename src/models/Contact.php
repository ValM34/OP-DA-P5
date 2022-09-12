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

    public function getTitle(): ?string
    {
        return Contact::$contactTitle . $this->name . ' ' . $this->lastName . ' (' . $this->email . ') vous a envoyé: ' . $this->sujet;
    }
    public function getCorp(): ?string
    {
        return $this->corp;
    }
    public function getDest(): ?string
    {
        return $this->dest;
    }
    public function getHeaders(): ?string
    {
        return $this->headers;
    }

    public function setSujet($sujet): self
    {
        $this->sujet = $sujet;

				return $this;
    }
    public function setCorp($corp): self
    {
        $this->corp = $corp;

				return $this;
    }
    public function setName($name): self
    {
        $this->name = $name;

				return $this;
    }
    public function setlastName($lastName): self
    {
        $this->lastName = $lastName;

				return $this;
    }
    public function setEmail($email): self
    {
        $this->email = $email;

				return $this;
    }
    public function setDest($dest): self
    {
        $this->dest = $dest;

				return $this;
    }
    public function setHeaders($headers): self
    {
        $this->headers = $headers;

				return $this;
    }

    // Envoie le message de succès ou d'erreur à l'issue de l'envoi d'un mail
    public function sendEmail($dest, $sujet, $corp, $headers)
    {
        mail($dest, $sujet, $corp, $headers);
    }

}