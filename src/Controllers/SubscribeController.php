<?php

namespace Controllers;

use Models\User;
use Models\ConnectDb;
use Router\Helpers;

class SubscribeController
{
    public function display($numberOfPaths)
    {
        include('../src/templates/configTwig.php');
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        $twig->display('subscribe.twig', ['pathToPublic' => $path]);
    }
    public function suscribe($numberOfPaths)
    {
        $pathToPublic = new Helpers();
        $path = $pathToPublic->pathToPublic($numberOfPaths);
        $verify = $this->verifieSiLesChampsSontRemplis();
        if ($verify === false) {
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

            $connectDb = new ConnectDb();
            $user = $connectDb->getUser();
            $passwordDb = $connectDb->getPassword();
            $options = $connectDb->getOptions();
            $dataSourceName = $connectDb->getDataSourceName();
            try {
                $pdo = new \PDO($dataSourceName, $user, $passwordDb, $options);
                $getUsersQuery = 'SELECT * FROM users WHERE email = :email;';
                $getUsers = $pdo->prepare($getUsersQuery);
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
                    $addUser = $pdo->prepare($addUserQuery);
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
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), $e->getCode());
            }
        }
    }
    // Renvoie l'utilisateur
    public function getUser()
    {
        $user = new User();

        $user
            ->setName($_POST['name'])
            ->setSurname($_POST['surname'])
            ->setEmail($_POST['email'])
            ->setPassword($_POST['password']);

        return $user;
    }

    public function verifieSiLesChampsSontRemplis()
    {
        if (!isset($_POST['name'])) {
            $verify = false;
            return $verify;
        } else {
            if (
                !$_POST['name'] ||
                !$_POST['surname'] ||
                !$_POST['email'] ||
                !$_POST['password']
            ) {
                $verify = false;
            } else {
                $verify = true;
            }
            return $verify;
        }
    }
}
