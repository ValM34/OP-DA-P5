<?php

namespace Globals;

/*
class Globals
{
    public ?array $SERVER = null;
    public ?array $COOKIE = null;
    public ?array $GET = null;
    public ?array $FILES = null;
    public ?array $POST = null;
    public ?array $SESSION = null;

    public function __construct()
    {
        $this->SERVER = array_map('htmlspecialchars', $_SERVER);
        $this->GET = array_map('htmlspecialchars', $_GET);
        $this->FILES = array_map('htmlspecialchars', $_FILES);
        $this->POST = array_map('htmlspecialchars', $_POST);
        $this->COOKIE = array_map('htmlspecialchars', $_COOKIE);
        $this->SESSION = array_map('htmlspecialchars', $_SESSION['user']);
    }
}
*/
Class Globals
{
    private $GET;

    public function __construct()
    {
        $this->GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_ENCODED);
        $this->ENV = filter_input_array(INPUT_ENV);
    }

    public function getGET()
    {
        return $this->GET;
    }

    public function getENV()
    {
        return $this->ENV;
    }

    public function setENV($ENV)
    {
        $this->ENV = $ENV;

        return $ENV;
    }
}

?>