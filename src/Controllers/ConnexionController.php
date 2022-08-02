<?php

namespace Controllers;

use Router\Helpers;
use Models\User;
use Models\ConnectDb;

class ConnexionController
{
    public function display($numberOfPaths)
    {
        include('../src/templates/configTwig.php');
        $message = '';
        $errorMessage = '';
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
        }
        if (isset($_GET['errorMessage'])) {
            $errorMessage = $_GET['errorMessage'];
        }
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        if (!empty($_SESSION['logged'])) {
            header("location: " . $path . "accueil");
        } else {
            $twig->display('connexion.twig', ['message' => $message, 'errorMessage' => $errorMessage, 'pathToPublic' => $path]);
        }
    }

    public function connexion()
    {
        $user = $this->getUser();
        $email = $user->getEmail();
        $password = $user->getPassword();

        $connectDb = new ConnectDb();
        $userDb = $connectDb->getUser();
        $passwordDb = $connectDb->getPassword();
        $options = $connectDb->getOptions();
        $dataSourceName = $connectDb->getDataSourceName();

        if (!$email || !$password) {
            echo 'erreur';
        } else {
            $pdo = new \PDO($dataSourceName, $userDb, $passwordDb, $options);
            $getUserQuery = 'SELECT email, password, id FROM users WHERE email = :email;';
            $getUser = $pdo->prepare($getUserQuery);
            $getUser->execute([
                'email' => $email
            ]);
            $fetchUser = $getUser->fetchAll();

            if (isset($fetchUser[0]['id'])) {
                $idUser = json_encode($fetchUser[0]['id']);

                $_SESSION['id'] = $idUser;
                echo $idUser;

                $verifyHash = password_verify($password, $fetchUser[0]['password']);

                if ($verifyHash !== true) {
                    echo "c'est pas égal connexion refusée";
                    $_GET['message'] = "connexionFailed";
                    $_GET['errorMessage'] = "passwordError";
                } else {
                    echo 'Connexion acceptée !';
                    $_GET['message'] = 'loggedSuccessfully';
                    // Ajouter le cookie pour connecter l'utilisateur --------------------------
                    $_SESSION['logged'] = 1;
                }
            } else {
                $_GET['message'] = "connexionFailed";
                echo $_GET['message'];
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
