<?php

namespace Controllers;

use Router\Helpers;
use Models\User;
use Models\ConnectDb;

class ConnexionController
{
    // Affiche le formulaire de connexion
    public function display($numberOfPaths, $isLogged)
    {
        include('../src/templates/configTwig.php');
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        if (!empty($_SESSION['logged'])) {
            header("location: " . $path . "accueil");
        } else {
            $errorMessage = isset($_GET['errorMessage']) ? $_GET['errorMessage'] : "";
            if(1 === $isLogged) {
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



        // L'utilisateur n'est pas défini
        if (null === $email || null === $password) {
            echo 'erreur';
        // L'utilisateur est défini
        } else {
            $connectDb = new ConnectDb();
            $userDb = $connectDb->getUser();
            $passwordDb = $connectDb->getPassword();
            $options = $connectDb->getOptions();
            $dataSourceName = $connectDb->getDataSourceName();

            $pdo = new \PDO($dataSourceName, $userDb, $passwordDb, $options);
            $getUserQuery = 'SELECT email, password, id, role FROM users WHERE email = :email;';
            $getUser = $pdo->prepare($getUserQuery);
            $getUser->execute([
                'email' => $email
            ]);
            $fetchUser = $getUser->fetchAll();

            if (isset($fetchUser[0]['id'])) {
                $idUser = $fetchUser[0]['id'];
                $_SESSION['id'] = $idUser;
                // $_SESSION['user']['id'] = $idUser;
                $verifyHash = password_verify($password, $fetchUser[0]['password']);

                if ($verifyHash !== true) {
                    $_GET['message'] = "connexionFailed";
                    $_GET['errorMessage'] = "passwordError";
                } else {
                    $_GET['message'] = 'loggedSuccessfully';
                    // Mettre true à la place de 1;
                    $_SESSION['logged'] = 1;
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
        $pathToPublic = new Helpers;
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        header("location:" . $path . "accueil");
    }

    public function getUser()
    {
        $user = new User();

        $user
            ->setEmail($_POST['email'])
            ->setPassword($_POST['password']);

        return $user;
    }
}
