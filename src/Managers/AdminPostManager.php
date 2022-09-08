<?php

namespace Managers;

use PDO;
use PDOException;
use Models\Post;
use Models\Comment;
use Models\ConnectDb;
use Globals\Globals;

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

  public function readAllPosts()
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT * FROM blog_posts ORDER BY created_at desc');
    $this->pdoStatement->execute();
    $allPost = $this->pdoStatement->fetchAll();

    return $allPost;
  }

  public function hidePost($id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE blog_posts SET status = "hidden" WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_post]);

    return;
  }

  public function publishPost($id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE blog_posts SET status = "published" WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_post]);

    return;
  }

  public function getImgSrc($id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT img_src FROM blog_posts WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_post]);
    $imgSrc = $this->pdoStatement->fetch();

    return $imgSrc['img_src'];
  }

  public function deletePost($id_post)
  {
    $this->pdoStatement = $this->pdo->prepare('DELETE FROM blog_posts WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_post]);

    return;
  }

  public function getAdmins()
  {
    $this->pdoStatement = $this->pdo->prepare('SELECT id, name, surname FROM users WHERE role = "admin"');
    $this->pdoStatement->execute();
    $admins = $this->pdoStatement->fetchAll();

    return $admins;
  }

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

  public function publishComment($id_comment)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE comments SET status = "published" WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_comment]);

    return;
  }

  public function rejectComment($id_comment)
  {
    $this->pdoStatement = $this->pdo->prepare('UPDATE comments SET status = "rejected" WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_comment]);

    return;
  }

  public function deleteComment($id_comment)
  {
    $this->pdoStatement = $this->pdo->prepare('DELETE from comments WHERE id = :id');
    $this->pdoStatement->execute(['id' => $id_comment]);

    return;
  }
}
