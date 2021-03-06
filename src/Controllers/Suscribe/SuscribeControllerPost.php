<?php

namespace Controllers\Suscribe;

use Models\User;
use Models\ConnectDb;
use Router\PathToPublic;

class SuscribeControllerPost
{
    public function suscribe($nombreDeMots)
    {

        $pathToPublic = new PathToPublic();
        $path = $pathToPublic->link($nombreDeMots);
        if (
            !isset($_POST['name']) ||
            !isset($_POST['surname']) ||
            !isset($_POST['email']) ||
            !isset($_POST['password'])
        ) {
            include_once(__DIR__ . '/../../templates/configTwig.php');
            echo $twig->render('suscribe.twig', [
                'suscribedSuccessfully' => false,
                'suscribedFailed' => false,
                'pathToPublic' => $path
            ]);
            return;
        }
        $user = new User();

        $user->setName($_POST['name']);
        $user->setSurname($_POST['surname']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);

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
                include_once(__DIR__ . '/../../templates/configTwig.php');
                echo $twig->render('suscribe.twig', [
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
                include_once(__DIR__ . '/../../templates/configTwig.php');
                echo $twig->render('suscribe.twig', [
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
