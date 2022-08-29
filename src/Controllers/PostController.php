<?php

namespace Controllers;

use Models\ConnectDb;
use Router\Helpers;
use Globals\Globals;
use Models\Post;
use Models\Comment;

class PostController
{
	public function __construct()
	{
		$connectDb = new ConnectDb();
		$this->pdo = $connectDb->connect();
		$this->helpers = new Helpers();
		$this->path = $this->helpers->pathToPublic();
		$this->globals = new Globals;
		$this->post = new Post();
		$this->comment = new Comment();
	}

	// Affiche la liste des articles
	public function displayPostList()
	{
		$getPostListQuery = 'SELECT * FROM blog_posts;';
		$getPostList = $this->pdo->prepare($getPostListQuery);
		$getPostList->execute();
		$this->post->setPost($getPostList->fetchAll());
		$postList = $this->post->getPost();

		$userSession = $this->helpers->isLogged();
		$postList = $this->helpers->dateConverter($postList);
		include_once __DIR__ . '/../templates/configTwig.php';
		if (true === $userSession['logged']) {
			$twig->display('postList.twig', ['postList' => $postList, 'pathToPublic' => $this->path, 'userSession' => $userSession]);
		} else {
			$twig->display('postList.twig', ['postList' => $postList, 'pathToPublic' => $this->path, 'userSession' => $userSession]);
		}
	}

	// Affiche l'article séléctionné
	public function displayPost($id_post, $errorMsg)
	{
		$getPostQuery = '
                SELECT B.*, U.name, U.surname
                FROM blog_posts B
                JOIN users U
                ON B.idUser = U.id
                WHERE B.id = :id;
        ';
		$getPost = $this->pdo->prepare($getPostQuery);
		$getPost->execute(['id' => $id_post]);
		$this->post->setPost($getPost->fetchAll());
		$post = $this->post->getPost();
		$post = $this->helpers->dateConverter($post);
		$getCommentsQuery = '
                SELECT J.id, J.id_post, J.id_user, J.content, J.status, J.created_at, J.updated_at, J.id_user, P.name, P.surname
                FROM comments J 
                JOIN users P
                ON J.id_user = P.id
                WHERE id_post = :id_post
                ORDER BY created_at;
        ';
		$getComments = $this->pdo->prepare($getCommentsQuery);
		$getComments->execute([
			'id_post' => $id_post
		]);
		$this->comment->setComment($getComments->fetchAll());
		$comments = $this->comment->getComment();
		$comments = $this->helpers->dateConverter($comments);
		$userSession = $this->helpers->isLogged();

		include __DIR__ . '/../templates/configTwig.php';
		$twig->display('post.twig', [
			'post' => $post[0],
			'comments' => $comments,
			'pathToPublic' => $this->path,
			'userSession' => $userSession,
			'errorMsg' => $errorMsg
		]);
	}

	// Ajoute un commentaire
	public function addComment($id_post_owner)
	{
		$id = $id_post_owner;
		$userSession = $this->helpers->isLogged();
		if (true === $userSession['logged']) {
			$getCommentsQuery = '
                SELECT C.id, C.id_post, C.id_user, C.content, C.created_at, C.updated_at, U.name, U.surname
                FROM comments C 
                JOIN users U
                ON C.id_user = U.id
                WHERE id_post = :id_post
                ORDER BY created_at;
            ';
			$getComments = $this->pdo->prepare($getCommentsQuery);
			$getComments->execute([
				'id_post' => $id
			]);

			$id_user = $_SESSION['user']['id'];
			$id_post = $id;
			$post = $this->globals->getPOST('content');
			$cleanedPOST = strip_tags($post);
			$content = htmlspecialchars($cleanedPOST, FILTER_FLAG_NO_ENCODE_QUOTES);

			if (empty($content)) {
				$errorMsg = true;
				include __DIR__ . '/../templates/configTwig.php';
				$this->displayPost($id, $errorMsg);
				return;
			}
			$getAddPostQuery = 'INSERT INTO comments (id_post, id_user, content) VALUES (:id_post, :id_user, :content);';
			$getAddComment = $this->pdo->prepare($getAddPostQuery);
			$getAddComment->execute([
				'id_post' => $id_post,
				'id_user' => $id_user,
				'content' => $content
			]);
			$errorMsg = false;
			var_dump($this->globals->getSERVER('HTTP_REFERER'));
			header('location: ' . $this->globals->getSERVER('HTTP_REFERER'));
		} else {
			$errorMsg = false;
			include __DIR__ . '/../templates/configTwig.php';
			$this->displayPost($id, $errorMsg);
			return;
		}
	}
}
