<?php

namespace Models;

class User
{
  private $id;
  private $name;
  private $surname;
  private $email;
  private $password;
  private $role;

  public function __construct($id = null, $name = null, $surname = null, $email = null, $password = null, $role = null)
  {
    $this->id = $id;
    $this->name = $name;
    $this->surname = $surname;
    $this->email = $email;
    $this->password = $password;
    $this->role = $role;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

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

  public function getRole(): ?string
  {
    return $this->role;
  }

  public function setId(?int $id): self
  {
    $this->id = $id;

    return $this;
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

  public function setRole(?string $role): self
  {
    $this->role = $role;

    return $this;
  }
}
