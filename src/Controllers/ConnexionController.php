<?php
/*
namespace Controllers;

use Router\Helpers;
use Models\User;
use Models\ConnectDb;

class ConnexionController
{
    public function __construct()
    {
        $connectDb = new ConnectDb();
        $this->pdo = $connectDb->connect();
        $this->helpers = new Helpers();
    }

    // Affiche le formulaire de connexion
    public function display($numberOfPaths, $isLogged)
    {
        include('../src/templates/configTwig.php');
        $path = $this->helpers->pathToPublic($numberOfPaths);
        if (!empty($_SESSION['logged'])) {
            header("location: " . $path . "accueil");
        } else {
            $errorMessage = isset($_GET['errorMessage']) ? $_GET['errorMessage'] : "";
            if (true === $isLogged) {
                $twig->display('connexion.twig', ['errorMessage' => $errorMessage, 'pathToPublic' => $path, 'isLogged' => $isLogged]);
            } else {
                $twig->display('connexion.twig', ['errorMessage' => $errorMessage, 'pathToPublic' => $path, 'isLogged' => $isLogged]);
            }
        }
    }

    public function connexion()
    {
        $user = $this->getUser();
        $email = $user->getEmail();
        $password = $user->getPassword();

        if (null === $email || null === $password) {
            echo 'erreur';
        } else {
            $getUserQuery = 'SELECT email, password, id, role FROM users WHERE email = :email;';
            $getUser = $this->pdo->prepare($getUserQuery);
            $getUser->execute([
                'email' => $email
            ]);
            $fetchUser = $getUser->fetchAll();

            if (isset($fetchUser[0]['id'])) {
                $idUser = $fetchUser[0]['id'];
                $_SESSION['id'] = $idUser;
                // $_SESSION['user']['id'] = $idUser;
                $verifyHash = password_verify($password, $fetchUser[0]['password']);

                if (true !== $verifyHash) {
                    $_GET['message'] = "connexionFailed";
                    $_GET['errorMessage'] = "passwordError";
                } else {
                    $_GET['message'] = 'loggedSuccessfully';
                    // Mettre true à la place de 1;
                    $_SESSION['logged'] = true;
                    $_SESSION['role'] = $fetchUser[0]['role'];
                }
            } else {
                $_GET['message'] = "connexionFailed";
                $_GET['errorMessage'] = "emailError";
            }
        }
    }

    function deconnexion($numberOfPaths)
    {
        session_unset();
        session_destroy();
        $path = $this->helpers->pathToPublic($numberOfPaths);
        header("location:" . $path . "accueil");
    }

    public function getUser()
    {
        $user = new User();

        $user
            ->setEmail(htmlspecialchars($_POST['email']))
            ->setPassword(htmlspecialchars($_POST['password']));

        return $user;
    }
}
*/