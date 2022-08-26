<?php

namespace Controllers;

use Models\User;
use Models\ConnectDb;
use Router\Helpers;
use Globals\Globals;

class UserController
{
	public function __construct()
	{
		$connectDb = new ConnectDb();
		$this->pdo = $connectDb->connect();
		$this->helpers = new Helpers();
		$this->path = $this->helpers->pathToPublic();
		$this->userSession = $this->helpers->isLogged();
		$this->globals = new Globals();
	}

	// Affiche la page d'inscription
	public function displaySubscription()
	{
		include '../src/templates/configTwig.php';
		$twig->display('subscribe.twig', ['pathToPublic' => $this->path, 'userSession' => $this->userSession]);
	}

	// Inscrit un utilisateur
	public function suscribe()
	{
		$verify = $this->verifieSiLesChampsSontRemplis();
		if (false === $verify) {
			include_once __DIR__ . '../../templates/configTwig.php';
			$twig->display('subscribe.twig', [
				'suscribedSuccessfully' => false,
				'suscribedFailed' => false,
				'pathToPublic' => $this->path,
				'userSession' => $this->userSession
			]);
			return;
		} else {
			$user = $this->getUser();
			$name = $user->getName();
			$surname = $user->getSurname();
			$email = $user->getEmail();
			$password = $user->getPassword();

			$getUsersQuery = 'SELECT * FROM users WHERE email = :email;';
			$getUsers = $this->pdo->prepare($getUsersQuery);
			$getUsers->execute(['email' => $email]);
			$fetchUsers = $getUsers->fetchAll();
			if (isset($fetchUsers[0])) {
				$suscribedFailed = "L'email existe déjà!";
				include_once __DIR__ . '../../templates/configTwig.php';
				$twig->display('subscribe.twig', [
					'suscribedSuccessfully' => false,
					'suscribedFailed' => $suscribedFailed,
					'pathToPublic' => $this->path,
					'userSession' => $this->userSession
				]);
			} else {
				$hash = password_hash($password, PASSWORD_DEFAULT);
				$addUserQuery = 'INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password);';
				$addUser = $this->pdo->prepare($addUserQuery);
				$addUser->execute([
					'name' => $name,
					'surname' => $surname,
					'email' => $email,
					'password' => $hash
				]);
				$suscribedSuccessfully = 'Votre inscription a bien été validée';
				include_once __DIR__ . '../../templates/configTwig.php';
				$twig->display('subscribe.twig', [
					'suscribedSuccessfully' => $suscribedSuccessfully,
					'suscribedFailed' => false,
					'pathToPublic' => $this->path,
					'userSession' => $this->userSession
				]);
			}
		}
	}

	// Renvoie l'utilisateur
	public function getUser()
	{
		$user = new User();

		$post['name'] = htmlspecialchars($this->globals->getPOST('name'));
		$post['surname'] = htmlspecialchars($this->globals->getPOST('surname'));
		$post['email'] = htmlspecialchars($this->globals->getPOST('email'));
		$post['password'] = htmlspecialchars($this->globals->getPOST('password'));
		$user
			->setName($post['name'])
			->setSurname($post['surname'])
			->setEmail($post['email'])
			->setPassword($post['password']);

		return $user;
	}

	// Vérifie si les champs remplis correctement
	public function verifieSiLesChampsSontRemplis()
	{
		$post['email'] = htmlspecialchars($this->globals->getPOST('email'));
		// Il faut une adresse email valide format : adresse@email.com
		$emailRegex = preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $post['email']);
		$post['password'] = htmlspecialchars($this->globals->getPOST('password'));
		// Password: il faut au minimum 8 caractères dont 1 chiffre, 1 lettre minuscule et une lettre majuscule
		$passwordRegex = preg_match('/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{7,})\S$/', $post['password']);

		$post['name'] = htmlspecialchars($this->globals->getPOST('name'));
		$post['surname'] = htmlspecialchars($this->globals->getPOST('surname'));
		if (!isset($post['name'])) {
			$verify = false;
			return $verify;
		} else {
			if (
				empty($post['name']) ||
				empty($post['surname']) ||
				$emailRegex !== 1 ||
				$passwordRegex !== 1
			) {
				$verify = false;
			} else {
				$verify = true;
			}
			return $verify;
		}
	}

	// Vérifie si l'email est rempli correctement
	public function verifyEmail()
	{
		$post['email'] = htmlspecialchars($this->globals->getPOST('email'));
		// Il faut une adresse email valide format : adresse@email.com
		$emailRegex = preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $post['email']);

		if (!isset($post['email'])) {
			$verify = false;
			return $verify;
		} else {
			if (
				$emailRegex !== 1
			) {
				$verify = false;
			} else {
				$verify = true;
			}
			return $verify;
		}
	}

	// Vérifie si le mot de passe est rempli correctement
	public function verifyPassword()
	{
		$post['password'] = htmlspecialchars($this->globals->getPOST('password'));
		// Password: il faut au minimum 8 caractères dont 1 chiffre, 1 lettre minuscule et une lettre majuscule
		$passwordRegex = preg_match('/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{7,})\S$/', $post['password']);

		if (!isset($post['password'])) {
			$verify = false;
			return $verify;
		} else {
			if (
				$passwordRegex !== 1
			) {
				$verify = false;
			} else {
				$verify = true;
			}
			return $verify;
		}
	}



	// Affiche le formulaire de connexion
	public function displayConnexion()
	{
		include '../src/templates/configTwig.php';
		if (true === $this->globals->SESSION['user']['logged']) {
			header('location: ' . $this->path . 'accueil');
		} else {
			if ($this->globals->getGET('errorMessage')) {
				$get['errorMessage'] = htmlspecialchars($this->globals->getGET('errorMessage'));
			}
			$errorMessage = isset($get['errorMessage']) ? $get['errorMessage'] : '';
			$twig->display('connexion.twig', ['errorMessage' => $errorMessage, 'pathToPublic' => $this->path, 'userSession' => $this->userSession]);
		}
	}

	// Connecte l'utilisateur
	public function connexion()
	{
		$user = new User();
		$post['email'] = htmlspecialchars($this->globals->getPOST('email'));
		$post['password'] = htmlspecialchars($this->globals->getPOST('password'));
		$user->setEmail($post['email']);
		$user->setPassword($post['password']);
		$email = $user->getEmail();
		$password = $user->getPassword();

		include '../src/templates/configTwig.php';
		if (null !== $email || null !== $password) {
			$verifyEmail = $this->verifyEmail();
			$verifyPassword = $this->verifyPassword();

			if (false === $verifyEmail) {
				$twig->display('connexion.twig', ['errorMessage' => 'emailError', 'pathToPublic' => $this->path, 'userSession' => $this->userSession]);
				return;
			} elseif (false === $verifyPassword) {
				$twig->display('connexion.twig', ['errorMessage' => 'passwordError', 'pathToPublic' => $this->path, 'userSession' => $this->userSession]);
				return;
			} else {
				$getUserQuery = 'SELECT email, password, id, role FROM users WHERE email = :email;';
				$getUser = $this->pdo->prepare($getUserQuery);
				$getUser->execute([
					'email' => $email
				]);
				$fetchUser = $getUser->fetchAll();

				if (isset($fetchUser[0]['id'])) {
					$idUser = $fetchUser[0]['id'];
					$_SESSION['user']['id'] = $idUser;
					$verifyHash = password_verify($password, $fetchUser[0]['password']);

					if (true !== $verifyHash) {
						$errorMessage = 'passwordError';
						$twig->display('connexion.twig', ['errorMessage' => $errorMessage, 'pathToPublic' => $this->path, 'userSession' => $this->userSession]);
					} else {
						$_SESSION['user']['logged'] = true;
						$_SESSION['user']['role'] = $fetchUser[0]['role'];
						header('location: ' . $this->path . 'accueil');
					}
				} else {
					$errorMessage = 'emailError';
					$twig->display('connexion.twig', ['errorMessage' => $errorMessage, 'pathToPublic' => $this->path, 'userSession' => $this->userSession]);
				}
			}
		}
	}

	// Déconnecte l'utilisateur
	public function deconnexion()
	{
		session_unset();
		session_destroy();
		header('location:' . $this->path . 'accueil');
	}
}
