<?php

namespace Models;

class User
{
    private $name;
    private $surname;
    private $email;
    private $password;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }
		
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
}