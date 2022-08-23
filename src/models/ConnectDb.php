<?php

namespace Models;

use PDO;
use PDOException;
use Globals\Globals;

class ConnectDb
{
	// Se connecte à la base de données
	public function connect()
	{
		try {
			$globals = new Globals();
			return new PDO(
				$globals->getENV('dataSourceName'),
				$globals->getENV('userDb'),
				$globals->getENV('passwordDb'),
				[
					PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES     => false,
				]
			);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), $e->getCode());
		}
	}
}
