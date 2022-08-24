<?php

namespace Router;

use Globals\Globals;

class Helpers
{
	// private $dateConverter;
	// private $adminLink;

	public function __construct()
	{
		$this->globals = new Globals();
		$this->globals->getENV('adminLink');
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

	// Défini le nombre de '/' après public pour retourner un nombre de '../' équivalent
	public function pathToPublic()
	{
		$url = $this->globals->getSERVER('REQUEST_URI');
		$newUrl = str_replace('/op-da-p5/public/', '', $url);
		$arrayUrl = explode('/', $newUrl);
		$numberOfPaths = count($arrayUrl);
		$link = '';
		$link2 = $link;
		for ($i = 1; $i < $numberOfPaths; $i++) {
			$link2 = sprintf('%s%s', $link, '../');
			$link = $link2;
		}
		return $link;
	}

	// Vérifie si un utilisateur est connecté
	public function isLogged()
	{
		if (isset($this->globals->getSESSION()['user'])) {
			$session['user'] = $this->globals->getSESSION('user');
			if (isset($session['user']['logged']) & $session['user']['logged'] === true & isset($session['user']['role']) & $session['user']['role'] === 'admin') {
				$session['user']['adminLink'] = $this->globals->getENV('adminLink');
				return $session['user'];
			} elseif (isset($session['user']['logged']) & $session['user']['logged'] === true) {
				return $session['user'];
			} else {
				return $session['user'];
			}
		} else {
			$session['user'] = $this->globals->getSESSION('user');
			return $session['user'];
		}
	}

	// Vérifie si un utilisateur est administrateur
	public function isAdmin()
	{
		$session['user'] = $this->globals->getSESSION('user');
		if (isset($session['user']['role'])) {
			if ($session['user']['role'] !== 'admin') {
				include('../src/templates/configTwig.php');
				$path = $this->pathToPublic();
				$twig->display('errorPage.twig', ['pathToPublic' => $path]);
				die;
			}
		} else {
			include('../src/templates/configTwig.php');
			$path = $this->pathToPublic();
			$twig->display('errorPage.twig', ['pathToPublic' => $path]);
			die;
		}
	}

	// Converti un format de date MySQL en un format français et lisible
	public function dateConverter($array)
	{
		for ($i = 0; $i < count($array); $i++) {
			if (isset($array[$i]['created_at'])) {
				$date = str_replace(['-', ':'], [' ', ' '], $array[$i]['created_at']);
				$fragmentedDate = explode(' ', $date);
				$array[$i]['created_at'] = $fragmentedDate[2] . '/' . $fragmentedDate[1] . '/' . $fragmentedDate[0] . ' à ' . $fragmentedDate[3] . ':' . $fragmentedDate[4];
			}
			if (isset($array[$i]['updated_at'])) {
				$date = str_replace(['-', ':'], [' ', ' '], $array[$i]['updated_at']);
				$fragmentedDate = explode(' ', $date);
				$array[$i]['updated_at'] = $fragmentedDate[2] . '/' . $fragmentedDate[1] . '/' . $fragmentedDate[0] . ' à ' . $fragmentedDate[3] . ':' . $fragmentedDate[4];
			}
		}
		return $array;
	}

	// Nettoie et sécurise les informations d'une variable
	/*public function cleaner($arg) {
        return htmlspecialchars(strip_tags($arg));
    }*/
}
