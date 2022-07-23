<?php

namespace Models;

echo 'je suis sur connect db';
/*$host = 'localhost';
$database = 'blogp5';
$user = 'root';
$password = 'root';
$port = '3306';
$charset = 'UTF8';
/* data source name *//*
$dataSourceName = "mysql:host=$host;dbname=$database;port=$port;charset=$charset";
$options = [
    \PDO::ATTR_ERRMODE              => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE   => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES     => false,
];

try {
    $pdo = new \PDO($dataSourceName, $user, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException ($e->getMessage(), $e->getCode());
}
*/
class ConnectDb
{
    private static $host = 'localhost';
    private static $database = 'blogp5';
    private static $user = 'root';
    private static $password = 'root';
    private static $port = '3306';
    private static $charset = 'UTF8';
    private static $options = [
        \PDO::ATTR_ERRMODE              => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE   => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES     => false,
    ];
    private static $dataSourceName = "mysql:host=localhost;dbname=blogp5;port=3306;charset=UTF8";

    // private $dataSourceName;
    private $pdo;

    public function getHost()
    {
        return ConnectDb::$host;
    }
    public function getDatabase()
    {
        return ConnectDb::$database;
    }
    public function getUser()
    {
        return ConnectDb::$user;
    }
    public function getPassword()
    {
        return ConnectDb::$password;
    }
    public function getPort()
    {
        return ConnectDb::$port;
    }
    public function getCharset()
    {
        return ConnectDb::$charset;
    }
    /*public function getDataSourceName()
    {
        return $this->dataSourceName;
    }*/
    public function getDataSourceName()
    {
        return ConnectDb::$dataSourceName;
    }
    public function getOptions()
    {
        return ConnectDb::$options;
    }
    public function getPdo()
    {
        return $this->pdo;
    }
    /*
    public function setDataSourceName()
    {
        $connectDb = new ConnectDb();
        $host = $connectDb->getHost();
        $database = $connectDb->getDatabase();
        $port = $connectDb->getPort();
        $charset = $connectDb->getCharset();
        return "mysql:host=" . ConnectDb::$host . ";dbname=" . ConnectDb::$database . ";port=" . ConnectDb::$port . ";charset=" . ConnectDb::$charset;
    }
    */
    

    
}
echo '(models)';
