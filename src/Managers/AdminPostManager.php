<?php

namespace Managers;

use Models\Post;
use Models\ConnectDb;

class AdminPostManager
{
  private $pdo;
  private $pdoStatement;

  public function __construct()
  {
    $connectDb = new ConnectDb();
    $this->pdo = $connectDb->connect();
    $this->post = new Post();
  }

  // Récupère tous les posts en BDD classés du plus récent au plus ancien
  public function readAllPosts()
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT * FROM blog_posts ORDER BY created_at desc');
    $this->pdoStatement->execute();
    $allPost = $this->pdoStatement->fetchAll();

    return $allPost;
  }

  // Change le statut d'un post en "hidden" en BDD
  public function hidePost($id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE blog_posts SET status = "hidden" WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_post]);

    return;
  }

  // Change le statut d'un post en "published" en BDD
  public function publishPost($id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE blog_posts SET status = "published" WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_post]);

    return;
  }

  // Retourne le chemin vers l'image séléctionnée
  public function getImgSrc($id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT img_src FROM blog_posts WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_post]);
    $imgSrc = $this->pdoStatement->fetch();

    return $imgSrc['img_src'];
  }

  // Supprime le post en BDD en fonction de l'id séléctionné
  public function deletePost($id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('DELETE FROM blog_posts WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_post]);

    return;
  }

  // Récupère tous les utilisateurs ayant le rôle "admin" en BDD
  public function getAdmins()
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT id, name, surname FROM users WHERE role = "admin"');
    $this->pdoStatement->execute();
    $admins = $this->pdoStatement->fetchAll();

    return $admins;
  }

  // Ajoute un post en BDD
  public function addPost(?int $idUser, ?string $title, ?string $content, ?string $chapo, ?string $src)
  {
    $this->pdoStatement = $this->pdo->prepare('INSERT INTO blog_posts (idUser, title, content, chapo, img_src) VALUES (:idUser, :title, :content, :chapo, :img_src)');
    $this->pdoStatement->execute([
      'idUser' => $idUser,
      'title' => $title,
      'content' => $content,
      'chapo' => $chapo,
      'img_src' => $src
    ]);

    return;
  }

  // Met à jour un post en BDD
  public function updatePost(?int $id_post, ?int $idUser, ?string $title, ?string $content, ?string $chapo, ?string $src)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE blog_posts SET title = :title, content = :content, chapo = :chapo, idUser = :idUser, img_src = :img_src, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
    $this->pdoStatement->execute([
      'id' => $id_post,
      'title' => $title,
      'content' => $content,
      'chapo' => $chapo,
      'img_src' => $src,
      'idUser' => $idUser
    ]);

    return;
  }

  // Récupère les informations d'un post en fonction de l'id envoyé en argument
  public function getPostData(?int $id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('
      SELECT 
      U.name, U.surname,
      B.*
      FROM comments C, users U, blog_posts B
      WHERE B.idUser = U.id
      AND B.id = :id
    ');
    $this->pdoStatement->execute(['id' => $id_post]);
    $postData = $this->pdoStatement->fetch();

    return $postData;
  }

  // Change le statut des posts envoyés dans la méthode en "published" dans la BDD
  public function publishSelectedComments($arrayIdPosts)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE blog_posts SET status = "published" WHERE ID = :id_post');
		foreach ($arrayIdPosts as $id_post) {
			$this->pdoStatement->execute([
				'id_post' => $id_post
			]);
		}

    return;
  }

  // Change le statut des posts envoyés dans la méthode en "hidden" dans la BDD
  public function hideSelectedComments($arrayIdPosts)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE blog_posts SET status = "hidden" WHERE ID = :id_post');
		foreach ($arrayIdPosts as $id_post) {
			$this->pdoStatement->execute([
				'id_post' => $id_post
			]);
		}

    return;
  }

  // Supprimme les posts envoyés dans la méthode de la BDD
  public function deleteSelectedComments($arrayIdPosts)
  {
    $this->pdoStatement = $this->pdo->prepare('DELETE FROM blog_posts WHERE ID = :id_post');
    $this->pdoStatement2 = $this->pdo->prepare('SELECT img_src FROM blog_posts WHERE id = :id');
    $arrayImgSrc = [];
		foreach ($arrayIdPosts as $id_post) {
			$this->pdoStatement2->execute(['id' => $id_post]);
      $post = $this->pdoStatement2->fetch();
			if ($post['img_src'] !== null) {
        $arrayImgSrc[] = $post['img_src'];
			}
			$this->pdoStatement->execute([
				'id_post' => $id_post
			]);
		}

    return $arrayImgSrc;
  }

  // Récupère tous les commentaires de la BDD classés du plus récent au plus ancien
  public function readAllComments()
  {
    $this->pdoStatement = $this->pdo->prepare('
      SELECT U.name, U.surname, C.id as commentId, C.created_at, C.updated_at, C.content, C.status, B.title as postTitle, B.id as postId 
      FROM comments C, users U, blog_posts B
      WHERE C.id_user = U.id
      AND C.id_post = B.id
      ORDER BY created_at DESC;
    ');
    $this->pdoStatement->execute();
    $allComments = $this->pdoStatement->fetchAll();

    return $allComments;
  }

  // Change le statut du commentaire envoyé dans la méthode en "published" de la BDD
  public function publishComment($id_comment)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE comments SET status = "published" WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_comment]);

    return;
  }

  // Change le statut du commentaire envoyé dans la méthode en "rejected" de la BDD
  public function rejectComment($id_comment)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE comments SET status = "rejected" WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_comment]);

    return;
  }

  // Supprime le commentaire envoyé dans la méthode de la BDD
  public function deleteComment($id_comment)
  {
    $this->pdoStatement = $this->pdo->prepare('DELETE from comments WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_comment]);

    return;
  }
}
