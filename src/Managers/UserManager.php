<?php

namespace Managers;

use PDO;
use PDOException;
use Models\User;
use Globals\Globals;
use Models\ConnectDb;

class UserManager
{
  private $pdo;
  private $pdoStatement;

  public function __construct()
  {
    $connectDb = new ConnectDb();
    $this->pdo = $connectDb->connect();
    $this->user = new User();
  }


  // Récupère tous les users de la BDD
  public function readAll()
  {
    $this->pdoStatement = $this->pdo->query('SELECT * FROM users ORDER BY name');
    $users = [];
    while ($user = $this->pdoStatement->fetchAll()) {
      $users[] = $user;
    }

    return $users;
  }

  // Vérifie si un email existe en BDD
  public function readEmail($email)
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT email from users WHERE email = :email');
    $this->pdoStatement->execute(['email' => $email]);
    $user = $this->pdoStatement->fetch();

    if ($user === false) {

      return false;
    } else {

      return true;
    }
  }

  // Ajoute un utilisateur en BDD
  public function addUser($name, $surname, $email, $hash)
  {
    $this->pdoStatement = $this->pdo->prepare('INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)');
    $this->pdoStatement->execute([
      'name' => $name,
      'surname' => $surname,
      'email' => $email,
      'password' => $hash
    ]);

    return;
  }

  // vérifie si un email existe en BDD
  public function connectUser($email)
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT email, password, id, role FROM users WHERE email = :email');
    $this->pdoStatement->execute([':email' => $email]);
    $user = $this->pdoStatement->fetch();

    if ($user !== null) {

      return $user;
    } else {

      return null;
    }
  }
}
