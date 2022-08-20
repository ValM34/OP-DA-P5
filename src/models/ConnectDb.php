<?php

namespace Models;

use PDO;
use PDOException;

class ConnectDb
{
    // Se connecte à la base de données
    public function connect()
    {
        try {
            return new PDO(
                $_ENV['dataSourceName'],
                $_ENV['userDb'],
                $_ENV['passwordDb'],
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
