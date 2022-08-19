<?php

namespace Controllers;

use Models\User;
use Models\ConnectDb;
use Router\Helpers;

class UserController
{
    public function __construct()
    {
        $connectDb = new ConnectDb();
        $this->pdo = $connectDb->connect();
        $this->helpers = new Helpers();
    }

    public function displaySubscription($numberOfPaths)
    {
        include('../src/templates/configTwig.php');
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();
        if (true === $userSession['logged']) {
            $twig->display('subscribe.twig', ['pathToPublic' => $path, 'userSession' => $userSession]);
        } else {
            $twig->display('subscribe.twig', ['pathToPublic' => $path]);
        }
    }

    public function suscribe($numberOfPaths)
    {
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $verify = $this->verifieSiLesChampsSontRemplis();
        if (false === $verify) {
            include_once(__DIR__ . '../../templates/configTwig.php');
            echo $twig->render('subscribe.twig', [
                'suscribedSuccessfully' => false,
                'suscribedFailed' => false,
                'pathToPublic' => $path
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
                include_once(__DIR__ . '../../templates/configTwig.php');
                echo $twig->render('subscribe.twig', [
                    'suscribedSuccessfully' => false,
                    'suscribedFailed' => $suscribedFailed,
                    'pathToPublic' => $path
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
                include_once(__DIR__ . '../../templates/configTwig.php');
                echo $twig->render('subscribe.twig', [
                    'suscribedSuccessfully' => $suscribedSuccessfully,
                    'suscribedFailed' => false,
                    'pathToPublic' => $path
                ]);
            }
        }
    }
    // Renvoie l'utilisateur
    public function getUser()
    {
        $user = new User();

        $user
            ->setName(htmlspecialchars($_POST['name']))
            ->setSurname(htmlspecialchars($_POST['surname']))
            ->setEmail(htmlspecialchars($_POST['email']))
            ->setPassword(htmlspecialchars($_POST['password']));

        return $user;
    }

    public function verifieSiLesChampsSontRemplis()
    {
        // Il faut une adresse email valide format : adresse@email.com
        $emailRegex = preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $_POST['email']);
        // Password: il faut au minimum 8 caractères dont 1 chiffre, 1 lettre minuscule et une lettre majuscule
        $passwordRegex = preg_match('/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{7,})\S$/', $_POST['password']);

        if (!isset($_POST['name'])) {
            $verify = false;
            return $verify;
        } else {
            if (
                !$_POST['name'] ||
                !$_POST['surname'] ||
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

    public function verifyEmail()
    {
        // Il faut une adresse email valide format : adresse@email.com
        $emailRegex = preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $_POST['email']);

        if (!isset($_POST['email'])) {
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

    public function verifyPassword()
    {
        // Password: il faut au minimum 8 caractères dont 1 chiffre, 1 lettre minuscule et une lettre majuscule
        $passwordRegex = preg_match('/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{7,})\S$/', $_POST['password']);

        if (!isset($_POST['password'])) {
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
    public function displayConnexion($numberOfPaths)
    {
        include('../src/templates/configTwig.php');
        $path = $this->helpers->pathToPublic($numberOfPaths);
        $userSession = $this->helpers->isLogged();
        if (!empty($_SESSION['user']['logged'])) {
            header("location: " . $path . "accueil");
        } else {
            $errorMessage = isset($_GET['errorMessage']) ? $_GET['errorMessage'] : "";
            if (true === $userSession['logged']) {
                $twig->display('connexion.twig', ['errorMessage' => $errorMessage, 'pathToPublic' => $path, 'userSession' => $userSession]);
            } else {
                $twig->display('connexion.twig', ['errorMessage' => $errorMessage, 'pathToPublic' => $path, 'userSession' => $userSession]);
            }
        }
    }

    public function connexion()
    {
        $user = new User();
        $user->setEmail(htmlspecialchars($_POST['email']));
        $user->setPassword(htmlspecialchars($_POST['password']));
        $email = $user->getEmail();
        $password = $user->getPassword();
        $path = $this->helpers->pathToPublic('dsqdqs');
        $userSession = $this->helpers->isLogged();

        include('../src/templates/configTwig.php');
        if (null !== $email || null !== $password) {
            $verifyEmail = $this->verifyEmail();
            $verifyPassword = $this->verifyPassword();

            if (false === $verifyEmail) {
                $path = $this->helpers->pathToPublic(12);
                $twig->display('connexion.twig', ['errorMessage' => 'emailError', 'pathToPublic' => $path, 'userSession' => $userSession]);
                return;

            } elseif (false === $verifyPassword) {
                $path = $this->helpers->pathToPublic(12);
                $twig->display('connexion.twig', ['errorMessage' => 'passwordError', 'pathToPublic' => $path, 'userSession' => $userSession]);
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
                        $errorMessage = "passwordError";
                        $twig->display('connexion.twig', ['errorMessage' => $errorMessage, 'pathToPublic' => $path, 'userSession' => $userSession]);
                    } else {
                        $_SESSION['user']['logged'] = true;
                        $_SESSION['user']['role'] = $fetchUser[0]['role'];
                        header("location: " . $path . "accueil");
                    }
                } else {
                    $errorMessage = "emailError";
                    $twig->display('connexion.twig', ['errorMessage' => $errorMessage, 'pathToPublic' => $path, 'userSession' => $userSession]);
                }
            }
        }
    }

    public function deconnexion($numberOfPaths)
    {
        session_unset();
        session_destroy();
        $path = $this->helpers->pathToPublic($numberOfPaths);
        header("location:" . $path . "accueil");
    }
}
