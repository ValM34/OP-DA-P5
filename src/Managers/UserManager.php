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

  // Créer un objet dans une BDD
  // Return true si l'user est créé, false si une erreur survient
  public function create($user)
  {
  }

  // Lit un objet stocké en BDD
  // Return true si l'user est créé, false si une erreur survient, null si ce qu'on m'a envoyé est null
  public function read($id)
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT * from users WHERE id = :id');

    // Liaison des paramètres
    $this->pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);

    // Execution de la requête
    $executeIsOk = $this->pdoStatement->execute();

    if ($executeIsOk) {
      // Récupération de notre résultat
      $user = $this->pdoStatement->fetchObject('Models\User');

      // Si fetchObject n'est aucun résultat il renvoie false donc pour le différencier avec l'erreur d'execution,
      // je le mets à null s'il me retourne false
      if ($user === false) {

        return null;
      } else {

        return $user;
      }
    } else {

      // erreur d'éxecution
      return false;
    }
  }

  // Récupère tous les users de la BDD
  // Return array d'objets User ou un tableau vide s'il n'y a aucun objet dans la bdd
  public function readAll()
  {
    $this->pdoStatement = $this->pdo->query('SELECT * FROM users ORDER BY name');

    // Construction d'un tableau d'objets de type User

    $users = [];

    while ($user = $this->pdoStatement->fetchAll()) {
      $users[] = $user;
    }

    return $users;
  }

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
