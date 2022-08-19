<?php

namespace Router;

class Helpers
{
    // private $dateConverter;
    private $adminLink;

    public function __construct()
    {
        $this->adminLink = $_ENV['adminLink'];
    }
    /*public function pathToPublic($numberOfPaths)
    {
        $link = '';
        $link2 = $link;
        for ($i = 1; $i < $numberOfPaths; $i++) {
            $link2 = sprintf("%s%s", $link, '../');
            $link = $link2;
        }
        return $link;
    }*/

    public function pathToPublic($rien)
    {
        $url = $_SERVER['REQUEST_URI'];
        $newUrl = str_replace("/op-da-p5/public/", "", $url);
        $arrayUrl = explode("/", $newUrl);
        $numberOfPaths = count($arrayUrl);
        $link = '';
        $link2 = $link;
        for ($i = 1; $i < $numberOfPaths; $i++) {
            $link2 = sprintf("%s%s", $link, '../');
            $link = $link2;
        }
        return $link;
    }

    public function isLogged()
    {
        if (isset($_SESSION['user'])) {
            if (isset($_SESSION['user']['logged']) & $_SESSION['user']['logged'] === true & isset($_SESSION['user']['role']) & $_SESSION['user']['role'] === 'admin') {
                echo 'Vous êtes connecté.';
                $_SESSION['user']['adminLink'] = $this->adminLink;
                return $_SESSION['user'];
            } elseif (isset($_SESSION['user']['logged']) & $_SESSION['user']['logged'] === true) {
                echo 'Vous êtes connecté.';
                return $_SESSION['user'];
            } else {
                echo 'Vous êtes déconnecté';
                return $_SESSION['user'];
            }
        } else {
            echo 'Vous êtes déconnecté';
            return $_SESSION['user'];
        }
    }

    public function isAdmin($numberOfPaths)
    {
        if (isset($_SESSION['user']['role'])) {
            if ($_SESSION['user']['role'] !== 'admin') {
                include('../src/templates/configTwig.php');
                $path = $this->pathToPublic($numberOfPaths);
                $twig->display('errorPage.twig', ['pathToPublic' => $path]);
                die;
            }
        } else {
            include('../src/templates/configTwig.php');
            $path = $this->pathToPublic($numberOfPaths);
            $twig->display('errorPage.twig', ['pathToPublic' => $path]);
            die;
        }
    }

    public function dateConverter($array)
    {
        for ($i = 0; $i < count($array); $i++) {
            $date = str_replace(["-", ":"], [" ", " "], $array[$i]['created_at']);
            $fragmentedDate = explode(" ", $date);
            $array[$i]['created_at'] = $fragmentedDate[2] . "/" . $fragmentedDate[1] . "/" . $fragmentedDate[0] . " à " . $fragmentedDate[3] . ":" . $fragmentedDate[4];
            if (isset($array[$i]['updated_at'])) {
                $date = str_replace(["-", ":"], [" ", " "], $array[$i]['updated_at']);
                $fragmentedDate = explode(" ", $date);
                $array[$i]['updated_at'] = $fragmentedDate[2] . "/" . $fragmentedDate[1] . "/" . $fragmentedDate[0] . " à " . $fragmentedDate[3] . ":" . $fragmentedDate[4];
            }
        }
        return $array;
    }
}
