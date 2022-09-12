<?php

namespace Managers;

use Models\Post;
use Models\ConnectDb;

class PostManager
{
  private $pdo;
  private $pdoStatement;

  public function __construct()
  {
    $connectDb = new ConnectDb();
    $this->pdo = $connectDb->connect();
    $this->post = new Post();
  }

  // Séléctionne tous les posts classés du plus récent au plus ancien
  public function readAll()
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT * FROM blog_posts ORDER BY created_at DESC');
    $this->pdoStatement->execute();
    $allPost = $this->pdoStatement->fetchAll();

    return $allPost;
  }

  // Récupère les informations d'un post en fonction de son id
  public function readOnePost(?int $id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('
      SELECT B.*, U.name, U.surname
      FROM blog_posts B
      JOIN users U
      ON B.idUser = U.id
      WHERE B.id = :id
    ');
    $this->pdoStatement->execute(['id' => $id_post]);
    $post = $this->pdoStatement->fetch();

    return $post;
  }

  // Renvoie les commentaires (et les informations des utilisateurs qui ont écrit les commentaire) d'un post en fonction de son id_post
  public function readComments(?int $id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('
    SELECT J.id, J.id_post, J.id_user, J.content, J.status, J.created_at, J.updated_at, J.id_user, P.name, P.surname
    FROM comments J 
    JOIN users P
    ON J.id_user = P.id
    WHERE id_post = :id_post
    ORDER BY created_at DESC
    ');
    $this->pdoStatement->execute(['id_post' => $id_post]);
    $comments = $this->pdoStatement->fetchAll();

    return $comments;
  }

  // Ajoute un commentaire en BDD
  public function addComment(?int $id_post, ?int $id_user, ?string $content)
  {
    $this->pdoStatement = $this->pdo->prepare(
      'INSERT INTO comments (id_post, id_user, content) 
      VALUES (:id_post, :id_user, :content)'
    );
    $this->pdoStatement->execute([
      'id_post' => $id_post,
      'id_user' => $id_user,
      'content' => $content
    ]);

    return;
  }
}
